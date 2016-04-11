<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\UserRegister;
use Application\Utilities\NumberPlay;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Authentication\AuthenticationService;
use Application\Form\UserLogin;
use Application\Adapter\EmpAuthAdapter;
use Application\Model\UserORM;
use Zend\Http\Request;
use Zend\Authentication\Result;

class AnonymousController extends AbstractActionController
{

    /**
     *
     * @var UserORM
     */
    protected $modelAccessor;

    /**
     *
     * @var AuthenticationService
     */
    protected $empAuthServiceService;

    /**
     *
     * @var Container
     */
    protected $userInfoContainer;

    public function onDispatch(MvcEvent $e)
    {
        $userInfoContainer = new Container(USER_INFO_CONTAINER_NAME);
        $this->userInfoContainer = $userInfoContainer;
        $empAuthServiceService = $this->getServiceLocator()->get('EmpAuthService');
        $this->modelAccessor = $this->getServiceLocator()->get('UserModel');
        $this->empAuthServiceService = $empAuthServiceService;
        return parent::onDispatch($e);
    }

    public function signinAction()
    {
        $destination = null;
        $empAuthServiceService = $this->empAuthServiceService;
        $errors = false;
        $messages = null;
        $query = null;
        $router = $this->serviceLocator->get('Router');
        $form = new UserLogin();
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData(
                NumberPlay::cleaner($data, 
                    array(
                        'userPassword' => 'exclude'
                    )));
            if ($form->isValid()) {
                $formData = $form->getData();
                $formData = NumberPlay::cleaner(
                    NumberPlay::cleaner($data, 
                        array(
                            'userPassword' => 'exclude'
                        )));
                $loginId = $formData['userLoginId'];
                $password = $formData['userPassword'];
                $cryptAdapter = new EmpAuthAdapter($loginId, $password);
                $cryptAdapter->setServiceLocator($this->getServiceLocator());
                $result = $empAuthServiceService->authenticate($cryptAdapter);
                if (! $errors) {
                    $request = $this->getRequest();
                    if (! empty($request->getQuery())) {
                        $query = $request->getQuery();
                        if (! empty($query->get(REDIRECT_PARAM_NAME))) {
                            $destination = $query->get(REDIRECT_PARAM_NAME);
                            $request = new Request();
                            $request->setUri($destination);
                            $router = $this->getEvent()->getRouter();
                            $match = $router->match($request);
                            if (empty($match)) {
                                $destination = null;
                            }
                        }
                    }
                    switch ($result->getCode()) {
                        case Result::FAILURE_IDENTITY_NOT_FOUND:
                            $messages = $result->getMessages();
                            break;
                        case Result::FAILURE_CREDENTIAL_INVALID:
                            $messages = $result->getMessages();
                            break;
                        
                        case Result::SUCCESS:
                            $this->fillSession();
                            $messages = $result->getMessages();
                            if (empty($destination)) {
                                return $this->sentinel();
                            } else {
                                return $this->redirect()->toUrl($destination);
                            }
                            break;
                        
                        default:
                            $messages = $result->getMessages();
                            break;
                    }
                }
            } else {
                if (ENABLE_DEBUG_MODE) {
                    \Zend\Debug\Debug::dump($form->getMessages());
                }
            }
        } else {
            if (ENABLE_DEBUG_MODE == 'development') {
                if (! empty($form->getMessages())) {
                    \Zend\Debug\Debug::dump($form->getMessages());
                }
            }
        }
        $vm = new ViewModel();
        if (empty($messages)) {
            return $vm->setVariables(
                array(
                    'form' => $form,
                    'destination' => $destination
                ));
        } else {
            return $vm->setVariables(
                array(
                    'form' => $form,
                    'messages' => $messages,
                    'destination' => $destination
                ));
        }
    }

    private function sentinel()
    {
        $userInfoContainer = $this->userInfoContainer;
        $theLawKeeper = $this->empAuthServiceService;
        $memberId = $theLawKeeper->getIdentity();
        $accessLevel = $userInfoContainer->accessLevel;
        if (empty($memberId)) {
            return $this->redirect()->toRoute('home');
        } else {
            if ($accessLevel == 0) {
                return $this->redirect()->toRoute('anonymous/signin');
            }
            if ($accessLevel == 1) {
                return $this->redirect()->toRoute('user/dashboard');
            }
            if ($accessLevel == 6) {
                return $this->redirect()->toRoute('student');
            }
            if ($accessLevel == 7) {
                return $this->redirect()->toRoute('admin');
            }
            echo "Undefined Access level";
            die();
        }
    }

    private function fillSession()
    {
        /* @var $modelAccessor UserORM */
        $modelAccessor = $this->modelAccessor;
        $userInfoContainer = $this->userInfoContainer;
        $empAuthService = $this->empAuthServiceService;
        $uId = $empAuthService->getIdentity();
        $info = $modelAccessor->fetchAll(array(
            'id' => $uId
        ))[0];
        $info["sessionId"] = Container::getDefaultManager()->getId();
        $info["webHost"] = 'https://' . THIS_WEB_HOST;
        $userInfoContainer->userBasicAuthInfo = $info;
    }

    public function signupAction()
    {
        $form = new UserRegister();
        $errors = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $data = NumberPlay::cleaner($data, 
                array(
                    'password',
                    'passwordConfirm'
                ));
            $form->setData($data);
            if ($form->isValid()) {
                $formData = $form->getData();
                $password = $formData['password'];
                $confirm = $formData['passwordConfirm'];
                //
                $birthDay = $formData['birthDay'];
                $birthMonth = $formData['birthMonth'];
                $birthYear = $formData['birthYear'];
                //
                if (! checkdate($birthMonth, $birthDay, $birthYear)) {
                    $message = 'The date is invalid';
                    $form->get('birthDay')->setMessages(
                        array(
                            $message
                        ));
                    $errors = true;
                } else {
                    $formData['dateOfBirth'] = mktime(0, 0, 0, $birthMonth, $birthDay, $birthYear);
                }
                $formData['accessLevel'] = 1;
                $personalEmailId = $formData['personalEmailId'];
                $existingUserByEmail = $this->modelAccessor->fetchAll(
                    array(
                        'personalEmailId' => $personalEmailId
                    ))[0];
                if (! empty($existingUserByEmail)) {
                    $form->get('personalEmailId')->setMessages(
                        array(
                            'Email id is already registered'
                        ));
                    $errors = true;
                }
                //
                $loginId = $formData['loginId'];
                $existingUserByLoginId = $this->modelAccessor->fetchAll(
                    array(
                        'loginId' => $loginId
                    ))[0];
                if (! empty($existingUserByLoginId)) {
                    $form->get('loginId')->setMessages(
                        array(
                            'Login id is already taken'
                        ));
                    $errors = true;
                }
                //
                $mobile = $formData['mobile'];
                $existingUserBymobile = $this->modelAccessor->fetchAll(
                    array(
                        'mobile' => $mobile
                    ))[0];
                if (! empty($existingUserBymobile)) {
                    $form->get('mobile')->setMessages(
                        array(
                            'Phone Number Already Registered'
                        ));
                    $errors = true;
                }
                
                if ($password !== $confirm) {
                    $errors = true;
                    $form->get('passwordConfirm')->setMessages(
                        array(
                            'Passwords do not match'
                        ));
                }
                if (! $errors) {
                    $statuses = $this->modelAccessor->registeruser($formData);
                    if (is_array($statuses)) {
                        $form->setMessages($statuses);
                    } else {
                        $empAuthServiceService = $this->empAuthServiceService;
                        $cryptAdapter = new EmpAuthAdapter($loginId, $password);
                        $cryptAdapter->setServiceLocator($this->getServiceLocator());
                        $result = $empAuthServiceService->authenticate($cryptAdapter);
                        if (! $errors) {
                            $request = $this->getRequest();
                            if (! empty($request->getQuery())) {
                                $query = $request->getQuery();
                                if (! empty($query->get(REDIRECT_PARAM_NAME))) {
                                    $destination = $query->get(REDIRECT_PARAM_NAME);
                                    $request = new Request();
                                    $request->setUri($destination);
                                    $router = $this->getEvent()->getRouter();
                                    $match = $router->match($request);
                                    if (empty($match)) {
                                        $destination = null;
                                    }
                                }
                            }
                            switch ($result->getCode()) {
                                case Result::FAILURE_IDENTITY_NOT_FOUND:
                                    $messages = $result->getMessages();
                                    break;
                                case Result::FAILURE_CREDENTIAL_INVALID:
                                    $messages = $result->getMessages();
                                    break;
                                
                                case Result::SUCCESS:
                                    $this->fillSession();
                                    $messages = $result->getMessages();
                                    if (empty($destination)) {
                                        return $this->sentinel();
                                    } else {
                                        return $this->redirect()->toUrl($destination);
                                    }
                                    break;
                                
                                default:
                                    $messages = $result->getMessages();
                                    break;
                            }
                        }
                    }
                } else {
                    if (ENABLE_DEBUG_MODE) {
                        var_dump($formData);
                        var_dump($form->getMessages());
                    }
                }
            } else {
                if (ENABLE_DEBUG_MODE) {
                    echo "form is invalid";
                    var_dump($form->getMessages());
                }
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        return $view;
    }
}
