<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\UserORM;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class UserController extends AbstractActionController
{

    /**
     *
     * @var AuthenticationService
     */
    protected $empAuthServiceService;

    /**
     *
     * @var UserORM
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
        $this->modelAccessor = $this->getServiceLocator()->get('UserModel');
        $empAuthServiceService = $this->getServiceLocator()->get('EmpAuthService');
        $this->empAuthServiceService = $empAuthServiceService;
        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function dashboardAction()
    {
        return new ViewModel();
    }

    public function signoutAction()
    {
        $userInfoContainer = $this->userInfoContainer;
        $userInfoContainer->getManager()
            ->getStorage()
            ->clear(USER_INFO_CONTAINER_NAME);
        $empAuthServiceService = $this->empAuthServiceService;
        $empAuthServiceService->clearIdentity();
        session_destroy();
        return $this->redirect()->toRoute('home');
    }
}
