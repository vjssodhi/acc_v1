<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;
use Application\Model\AgentORM;
use Zend\Mvc\MvcEvent;
use Application\Form\AgentAdd;
use Application\Utilities\NumberPlay;
use Application\Exception\AuthenticationRequired;
use Zend\Json\Json;
use Zend\Http\Request;
use Application\Entity\Agent;
use Application\Form\AgentPayments;
use Application\Form\AssignAgent;

class AgentController extends AbstractActionController
{

    /**
     *
     * @var AuthenticationService
     */
    protected $empAuthServiceService;

    /**
     *
     * @var AgentORM
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
        $this->modelAccessor = $this->getServiceLocator()->get('AgentModel');
        $empAuthServiceService = $this->getServiceLocator()->get('EmpAuthService');
        $this->empAuthServiceService = $empAuthServiceService;
        return parent::onDispatch($e);
    }

    public function assignAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        
        $instituteId = $routeMatchParams['instituteId'];
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
        $allagents = $this->modelAccessor->fetchAllAgents(null);

        $agentOptions = array();
        if (! empty($allagents)) {
            foreach ($allagents as $agent) {
	

                $agentOptions[$agent['id']] = $agent['name'] . ', ' . $agent['emailId'] . '(Default Commission: ' . $agent['commissionPercentage'] . '%)';
            }
        }
        $form = new AssignAgent($agentOptions);
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $errors = false;
                $formData = $form->getData();
                $this->modelAccessor->addToInstitute($instituteId, $formData['agentId'], $formData);
                return $this->redirect()->toRoute('agent/list');
            }
        }
        $vm = new ViewModel(array(
            'instituteInfo' => $existingInstitute,
            'form' => $form
        ));
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $vm->addChild($vmMenu, 'adminSideMenu');
        return $vm;
    }

    public function indexAction()
    {
        $totalCommission = 0;
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        if (empty($routeMatchParams['agentId'])) {
            return $this->redirect()->toUrl('agent/list');
        } else {
            
            $agentId = $routeMatchParams['agentId'];
            $existingAgent = $this->modelAccessor->fetchAllAgents(array(
                'id' => $agentId
            ))[0];
            /* @var $agent Agent */
            
            if (empty($existingAgent)) {
                $errorMessage = 'Forbidden Resource';
                $event->setError(ERROR_NEED_AUTHENTICATED_USER);
                $event->setParam('exception', new AuthenticationRequired($errorMessage));
                $event->setParam('redirectUri', '/');
                return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
            }
            $agentAssocs = $this->modelAccessor->fetchInstituteAgents(array(
                'agentId' => $agentId
            ));
            if (empty($agentAssocs)) {
                $vm = new ViewModel(array(
                    'assocs' => false
                ));
                $vmMenu = new ViewModel();
                $vmMenu->setTemplate('application/menu/adminSideMenu');
                $vm->addChild($vmMenu, 'adminSideMenu');
                return $vm;
            }
            $institues = array();
            $agentEmail = $existingAgent['emailId'];
            $existingAgent['institutes'] = array();
            $existingAgent['students'] = array();
            foreach ($agentAssocs as $agentAssoc) {
                
                $institueInfo = $this->modelAccessor->fetchAllInstitutes(array(
                    'id' => $agentAssoc['instituteId']
                ))[0];
                $institues[$agentAssoc['instituteId']] = $institueInfo;
                $existingAgent['institutes'][] = $institueInfo;
            }
            $students = $this->modelAccessor->fetchAllStudents(array(
                'agentId' => $agentId
            ));
            
            $courseFeeCurrency = null;
            $totalStudents = 0;
            if (! empty($students)) {
                foreach ($students as $student) {
                    $totalStudents = $totalStudents + 1;
                    $existingAgent['students'][] = $student;
                    $cmsToPePaid = $student['commissionToBePaidByInstitute'];
                    $courseFeeCurrency = $student['feeCurrency'];
                    $totalCommission = $totalCommission + $cmsToPePaid;
                }
            }
            $test = NumberPlay::getAlphaNumericPassword(6);
            $container = new Container('transaction');
            if (empty($container->transactionSentinel)) {
                $container->transactionSentinel = $test;
            }
            $paymens = $this->modelAccessor->fetchAgentPaymentInfo(array(
                'emailId' => $agentEmail
            ));
            $paidAmount = 0;
            if (! empty($paymens)) {
                foreach ($paymens as $paymen) {
                    $p = intval($paymen['paidAmmount'], 10);
                    $paidAmount = $paidAmount + $p;
                }
            }
            
            $form = new AgentPayments($container->transactionSentinel);
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $errors = false;
                    $formData = $form->getData();
                    $newpaidAmount = $paidAmount + intval($formData['paymentAmount'], 10);
                    if ($newpaidAmount > $totalCommission) {
                        $form->get('paymentAmount')->setMessages(array(
                            sprintf('By paying %s, the net payment Amount becomes %s. Net payment cannot be greater than: %s', $formData['paymentAmount'], $newpaidAmount, $totalCommission)
                        ));
                        $errors = true;
                    }
                    if ($paidAmount >= $totalCommission) {
                        $message = 'All payment has been paid for this agent';
                    }
                    if ($formData['verifyAction'] !== $container->transactionSentinel) {
                        $form->get('verifyAction')->setMessages(array(
                            'Please recheck the transaction password'
                        ));
                        $errors = true;
                    }
                    if (! $errors) {
                        unset($container->transactionSentinel);
                        $this->modelAccessor->updatePayment($agentEmail, $totalCommission, $formData['paymentAmount']);
                        return $this->redirect()->toRoute('agent/details', array(
                            'agentId' => $agentId
                        ));
                    }
                } else {
                    var_dump($formData);
                    die();
                }
            }
            $vm = new ViewModel(array(
                'assocs' => true,
                'transactionPassword' => $container->transactionSentinel,
                'form' => $form,
                'totalCommission' => $totalCommission,
                'agentDetails' => $existingAgent,
                'institutes' => $institues,
                'paidAmount' => $paidAmount,
                'courseFeeCurrency' => $courseFeeCurrency
            ));
            $vmMenu = new ViewModel();
            $vmMenu->setTemplate('application/menu/adminSideMenu');
            $vm->addChild($vmMenu, 'adminSideMenu');
            return $vm;
        }
    }

    public function getagentsAction()
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
        $agentOptions = array();
        $allAgents = $this->modelAccessor->fetchAllAgents(array(
            'instituteId' => $instituteId
        ));
        if (! empty($allAgents)) {
            foreach ($allAgents as $agent) {
                $cmsPercentage = $agent['commissionPercentage'];
                $str = $agent['name'] . '(' . $agent['emailId'] . ', Default Commission: ' . $cmsPercentage . '%)';
                $agentOptions[$agent['id']] = $str;
            }
        } else {
            $agentOptions[0009] = 'No agents registered so far';
        }
        return $this->getResponse()->setContent(Json::encode($agentOptions));
    }

    private function test($agentEmail)
    {
        $agentsByEmail = $this->modelAccessor->fetchAllAgents(array(
            'emailId' => $agentEmail
        ));
        $agentDetails = array();
        $totalCommission = 0;
        foreach ($agentsByEmail as $key => $agentInfo) {
            $students = $this->modelAccessor->fetchAllStudents(array(
                'agentId' => $agentInfo['id']
            ));
            if (! empty($students)) {
                foreach ($students as $student) {
                    $cmsToPePaid = $student['commissionToBePaidByInstitute'];
                    $totalCommission = $totalCommission + $cmsToPePaid;
                }
            }
        }
        $paymens = $this->modelAccessor->fetchAgentPaymentInfo(array(
            'emailId' => $agentEmail
        ));
        $paidAmount = 0;
        if (! empty($paymens)) {
            foreach ($paymens as $paymen) {
                $p = intval($paymen['paidAmmount'], 10);
                $paidAmount = $paidAmount + $p;
            }
        }
        $in = array(
            'totalAmount' => $totalCommission,
            'paidAmount' => $paidAmount
        );
        return $in;
    }

    public function listAction()
    {
        ini_set('xdebug.var_display_max_depth', - 1);
        ini_set('xdebug.var_display_max_children', - 1);
        ini_set('xdebug.var_display_max_data', - 1);
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        $request = $this->getRequest();
        $allAgents = $this->modelAccessor->fetchAllAgents(null, null, null, null);
        // /
        if (! empty($allAgents)) {
            foreach ($allAgents as $k => $agnt) {
                $agentId = $agnt['id'];
                
                $agentAssocs = $this->modelAccessor->fetchInstituteAgents(array(
                    'agentId' => $agentId
                ));
                $agnt['paymentStatus'] = false;
                $agnt['institutes'] = array();
                $agnt['students'] = array();
                $agnt['totalCommission'] = 0;
                $courseFeeCurrency = 'AU';
                $agnt['courseFeeCurrency'] = $courseFeeCurrency;
                $agnt['paidAmount'] = 0;
                $agnt['paymentDue'] = 0;
                //
                $agnt['totalStudents'] = 0;
                //
                if (! empty($agentAssocs)) {
                    $institues = array();
                    $agentEmail = $agnt['emailId'];
                    $students = $this->modelAccessor->fetchAllStudents(array(
                        'agentId' => $agnt['id']
                    ));
                    
                    if (empty($students)) {
                        $totalStudents = 0;
                    } else {
                        $totalStudents = count($students);
                    }
                    foreach ($agentAssocs as $agentAssoc) {
                        
                        $institueInfo = $this->modelAccessor->fetchAllInstitutes(array(
                            'id' => $agentAssoc['instituteId']
                        ))[0];
                        $institues[$agentAssoc['instituteId']] = $institueInfo;
                        $agnt['institutes'][] = $institueInfo;
                    }
                    $paymInfo = $this->test($agentEmail);
                        $agnt['totalCommission'] = $paymInfo['totalAmount'];
                        $agnt['totalStudents'] = $totalStudents;
                        $agnt['institutes'] = $institues;
                        $agnt['paidAmount'] = $paymInfo['paidAmount'];
                        $agnt['courseFeeCurrency'] = $courseFeeCurrency;
                    if ($paymInfo['totalAmount'] == 0) {
                        $tc = 0;
                        $pDue = 0;
                        
                        $pDone = 0;
                    } else {
                        if ($paymInfo['paidAmount'] >= 0) {
                            $pDone = $paymInfo['paidAmount'];
                            $pDue = $paymInfo['totalAmount'] - $paymInfo['paidAmount'];
                        }
                    }
                    $agnt['paymentDue'] = $pDue;
                    if ($pDue > 0) {
                        $agnt['paymentStatus'] = 'PENDING';
                    }
                    $agnt['paidAmount'] = $pDone;
                }
                $allAgents[$k] = $agnt;
            }
        }
        // /
        $vm = new ViewModel(array(
            'allAgents' => $allAgents
        ));
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $vm->addChild($vmMenu, 'adminSideMenu');
        return $vm;
    }

    public function addAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        $request = $this->getRequest();
        $form = new AgentAdd();
        $errors = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $data = NumberPlay::cleaner($data);
            $form->setData($data);
            if ($form->isValid()) {
                $formData = NumberPlay::cleaner($form->getData());
                if (! $errors) {
                    $statuses = $this->modelAccessor->register($formData);
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
                            return $this->redirect()->toRoute('agent/list');
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
            'form' => $form
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
        $agentId = $routeMatchParams['agentId'];
        $existingAgent = $this->modelAccessor->fetchAllAgents(array(
            'id' => $agentId
        ))[0];
        if (empty($existingAgent)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        //
        $form = new AgentAdd();
        $form->setData($existingAgent);
        $errors = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $data = NumberPlay::cleaner($data);
            $form->setData($data);
            if ($form->isValid()) {
                $formData = NumberPlay::cleaner($form->getData());
                if (! $errors) {
                    $statuses = array();
                    $statuses = $this->modelAccessor->update($agentId, $formData);
                    if (is_array($statuses)) {
                        $form->setMessages($statuses);
                    } else {
                        return $this->redirect()->toRoute('agent/list');
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
        $view->setTemplate('application/agent/add');
        $vmMenu = new ViewModel();
        $vmMenu->setTemplate('application/menu/adminSideMenu');
        $view->addChild($vmMenu, 'adminSideMenu');
        return $view;
    }
}
