<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Application\Model\InvoiceORM;
use Application\Exception\AuthenticationRequired;

class InvoiceController extends AbstractActionController
{

    /**
     *
     * @var AuthenticationService
     */
    protected $empAuthServiceService;

    /**
     *
     * @var InvoiceORM
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
        $this->modelAccessor = $this->getServiceLocator()->get('InvoiceModel');
        $empAuthServiceService = $this->getServiceLocator()->get('EmpAuthService');
        $this->empAuthServiceService = $empAuthServiceService;
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }

    /**
     * Agent Specific Invoice
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function generateAction()
    {
        $event = $this->getEvent();
        $application = $event->getApplication();
        $router = $this->serviceLocator->get('Router');
        $routeMatch = $router->match($this->request);
        $routeMatchParams = $routeMatch->getParams();
        //
        $agentId = $routeMatchParams['agentId'];
        $agent = $this->modelAccessor->getStudentModel()
            ->getAgentModel()
            ->fetchAll(array(
            'id' => $agentId
        ))[0];
        if (empty($agent)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        //
        
        $studentId = $routeMatchParams['studentId'];
        $student = $this->modelAccessor->getStudentModel()->fetchAll(
            array(
                'id' => $studentId
            ))[0];
        if (empty($student)) {
            $errorMessage = 'Forbidden Resource';
            $event->setError(ERROR_NEED_AUTHENTICATED_USER);
            $event->setParam('exception', new AuthenticationRequired($errorMessage));
            $event->setParam('redirectUri', '/user/dashboard');
            return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
        }
        // The status can be paid or pending
        // but not certain about the point of generation
        // of Invoice
        
        $view = new ViewModel();
        return $view;
    }
}
