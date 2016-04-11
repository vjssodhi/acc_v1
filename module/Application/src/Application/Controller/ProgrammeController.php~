<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\ProgrammeORM;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Application\Utilities\NumberPlay;
use Application\Exception\AuthenticationRequired;
use Application\Form\ProgrammeAdd;
use Zend\Json\Json;
use Zend\Http\Request;

class ProgrammeController extends AbstractActionController
{

    /**
     *
     * @var AuthenticationService
     */
    protected $empAuthServiceService;

    /**
     *
     * @var ProgrammeORM
     */
    protected $modelAccessor;

    /**
     *
     * @var Container
     */
    protected $userInfoContainer;

    public function onDispatch(MvcEvent $e)
    {
        $this->userInfoContainer = new Container(USER_INFO_CONTAINER_NAME);
        $this->modelAccessor = $this->getServiceLocator()->get('ProgrammeModel');
        $empAuthServiceService = $this->getServiceLocator()->get('EmpAuthService');
        $this->empAuthServiceService = $empAuthServiceService;
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function getprogrammesAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        
        $routeMatchParams = $routeMatch->getParams();
        $instituteId = $routeMatchParams['instituteId'];
        
        $existingInstitute = $this->modelAccessor->fetchAllInstitutes(array(
            'id' => $instituteId
        ), null, true)[0];
        if (empty($existingInstitute)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        $programmeOptions = array();
        $allProgrammes = $this->modelAccessor->fetchAllProgrammes(array(
            'institute' => $existingInstitute
        ));
        if (! empty($allProgrammes)) {
            foreach ($allProgrammes as $programmeX) {
                $fees = $programmeX['feeAmount'];
                $feeCurrency = $programmeX['feeCurrency'];
                $strFees = $fees . ' ' . $feeCurrency;
                $helperStr = '(Fees:' . $strFees . ')';
                $programmeOptions[$programmeX['id']] = $programmeX['name'] . $helperStr;
            }
        } else {
            $programmeOptions[0009] = 'No programmes registered so far';
        }
        return $this->getResponse()->setContent(Json::encode($programmeOptions));
    }

    public function listallAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        
        $routeMatchParams = $routeMatch->getParams();
        $instituteId = $routeMatchParams['instituteId'];
        
        $existingInstitute = $this->modelAccessor->fetchAllInstitutes(array(
            'id' => $instituteId
        ), null, true)[0];
        if (empty($existingInstitute)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        $allProgrammes = $this->modelAccessor->fetchAllProgrammes(array(
            'institute' => $existingInstitute
        ));
        $view = new ViewModel(array(
            'allProgrammes' => $allProgrammes,
            'instituteId' => $instituteId,
            'institute' => $existingInstitute
        ));
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $view->addChild($vmMenu, 'adminSideMenu');
        return $view;
    }

    public function addAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        $request = $this->getRequest();
        $instituteId = $routeMatchParams['instituteId'];
        $existingInstitute = $this->modelAccessor->fetchAllInstitutes(array(
            'id' => $instituteId
        ))[0];
        
        if (empty($existingInstitute)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/programme/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        $form = new ProgrammeAdd();
        $errors = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $data = NumberPlay::cleaner($data);
            $form->setData($data);
            if ($form->isValid()) {
                $formData = NumberPlay::cleaner($form->getData());
                if (! $errors) {
                    $statuses = $this->modelAccessor->register($instituteId, $formData);
                    if (is_array($statuses)) {
                        $form->setMessages($statuses);
                    } else {
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
                        if (empty($destination)) {
                            return $this->redirect()->toRoute('programme/list', array(
                                'instituteId' => $instituteId
                            ));
                        } else {
                            return $this->redirect()->toUrl($destination);
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
            'form' => $form,
            'instituteDetails' => $existingInstitute
        ));
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $view->addChild($vmMenu, 'adminSideMenu');
        return $view;
    }

    public function updateAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        $instituteId = $routeMatchParams['instituteId'];

        $programmeId = $routeMatchParams['programmeId'];

        $existingInstitute = $this->modelAccessor->fetchAllInstitutes(array(
            'id' => $instituteId
        ))[0];
        if (empty($existingInstitute)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        $programmeId = $routeMatchParams['programmeId'];
        $existingprogramme = $this->modelAccessor->fetchAllProgrammes(array(
            'id' => $programmeId
        ))[0];
        $form = new ProgrammeAdd();
        //
        $form->setData($existingprogramme);
        $errors = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $data = NumberPlay::cleaner($data);
            $form->setData($data);
            if ($form->isValid()) {
                $formData = NumberPlay::cleaner($form->getData());
                if (! $errors) {

                   // $statuses = $this->modelAccessor->update($instituteId, $formData);
                   $statuses = $this->modelAccessor->update($programmeId, $formData);

                    if (is_array($statuses)) {
                        $form->setMessages($statuses);
                    } else {
                        return $this->redirect()->toRoute('programme/list', array(
                            'instituteId' => $instituteId
                        ));
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
            'form' => $form,
            'instituteDetails' => $existingInstitute
        ));
        $view->setTemplate('application/programme/add');
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $view->addChild($vmMenu, 'adminSideMenu');
        return $view;
    }
}
