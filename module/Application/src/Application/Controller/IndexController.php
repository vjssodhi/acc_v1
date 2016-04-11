<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\UserORM;
use Zend\Session\Container;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController
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
        if ($this->empAuthServiceService->hasIdentity()) {
            return $this->sentinel();
        }
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

    public function welcomeAction()
    {
        if ($this->empAuthServiceService->hasIdentity()) {
            return $this->sentinel();
        }
        return new ViewModel();
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
            if ($accessLevel == 7) {
                return $this->redirect()->toRoute('admin');
            }
            if ($accessLevel == 6) {
                return $this->redirect()->toRoute('student');
            }
            echo "Undefined Access level";
            die();
        }
    }
}
