<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Application\Model\UserORM;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Application\Exception\AuthenticationRequired;
use Application\Exception\AccessDenied;
use Application\View\RedirectionHandler;
use Application\Model\InstituteORM;
use Application\Model\ProgrammeORM;
use Application\Model\AgentORM;
use Application\Model\StudentORM;
use Application\Model\InvoiceORM;

class Module
{

    CONST THIS_MODULE_NAME = 'APPLICATION';

    CONST SESS_UQ = 'cba8e8d547deXcefe05826e9b8c985tuk19c0a5c68f4fb653e8a3d8aa';

    CONST AUTH_UQ = 'cba8e8d547deb653e8a3d8aa';

    public function onBootstrap(MvcEvent $event)
    {
        date_default_timezone_set('Asia/Kolkata');
        $this->bootstrapSession($event);
        /* @var $application \Zend\Mvc\ApplicationInterface */
        $application = $event->getApplication();
        $eventManager = $application->getEventManager();
        $serviceManager = $application->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $request = $serviceManager->get('request');
        $uri = $request->getUri();
        $host = $uri->getHost();
        defined('THIS_WEB_HOST') || define('THIS_WEB_HOST', $host);
        $u_config = $serviceManager->get('user_emp_eval_auth_config');
        $accessHandler = $serviceManager->get($u_config['restricted_access_handler']);
        $eventManager->attach($accessHandler);
        $redirectHandler = new RedirectionHandler();
        $eventManager->attach($redirectHandler);
        $eventManager->attach('route', array(
            $this,
            'empevalSentinel'
        ));
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function empevalSentinel(MvcEvent $event)
    {
        if (! file_exists(DATA_DIR)) {
            mkdir(DATA_DIR, 0777, true);
        }
        
        if (! file_exists(TEMP_ACL_DIR)) {
            try {
                mkdir(TEMP_ACL_DIR, 0777);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        if (! file_exists(ENC_CACHE_DIR)) {
            try {
                mkdir(ENC_CACHE_DIR, 0777);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }
        $application = $event->getApplication();
        // $eventManager = $application->getEventManager ();
        $serviceManager = $application->getServiceManager();
        $request = $serviceManager->get('request');
        // $response = $serviceManager->get ( 'response' );
        $uri = $request->getUri();
        // $host = $uri->getHost ();
        $routeMatchParams = $event->getRouteMatch()->getParams();
        $controller = $routeMatchParams['__CONTROLLER__'];
        $action = $routeMatchParams['action'];
        $namespace = strstr($routeMatchParams['__NAMESPACE__'], '\\', true);
        $theEmpAuthService = $serviceManager->get('EmpAuthService');
        
        $userInfo = new Container(USER_INFO_CONTAINER_NAME);
        $userAccessLevel = null;
        if (! $theEmpAuthService->hasIdentity()) {
            $userAccessLevel = 0;
            $userInfo->accessLevel = $userAccessLevel;
        } else {
            $userAccessLevel = $userInfo->accessLevel;
        }
        if ($namespace !== __NAMESPACE__) {
            return;
        }
        
        //
        $moduleCapitalised = strtoupper(static::THIS_MODULE_NAME);
        $anonymousResources = array(
            $moduleCapitalised . '.' . 'Index.index' => true,
            $moduleCapitalised . '.' . 'Anonymous.index' => true,
            $moduleCapitalised . '.' . 'Anonymous.signin' => true,
            $moduleCapitalised . '.' . 'Anonymous.signup' => true
        );
        $loggedInBaseActions = array(
            $moduleCapitalised . '.' . 'Index.index' => true,
            $moduleCapitalised . '.' . 'User.signout' => true,
            $moduleCapitalised . '.' . 'User.dashboard' => true
        );
        $adminResources = array(
            $moduleCapitalised . '.' . 'Admin.index' => true,
            $moduleCapitalised . '.' . 'Admin.adduser' => true,
            $moduleCapitalised . '.' . 'Admin.removeuser' => true,
            $moduleCapitalised . '.' . 'Admin.listusers' => true,
            $moduleCapitalised . '.' . 'Admin.updateusers' => true,
            $moduleCapitalised . '.' . 'Admin.updateuser' => true
        );
        
        $instituteManagerResources = array(
            $moduleCapitalised . '.' . 'Institute.index' => true,
            $moduleCapitalised . '.' . 'Institute.add' => true,
            $moduleCapitalised . '.' . 'Institute.update' => true,
            $moduleCapitalised . '.' . 'Institute.listall' => true,
            $moduleCapitalised . '.' . 'Institute.listcomponents' => true,
            $moduleCapitalised . '.' . 'Institute.deletefeecomponent' => true,
            $moduleCapitalised . '.' . 'Institute.updatefeestructure' => true,
            $moduleCapitalised . '.' . 'Institute.details' => true
        );
        $programmeManagerResources = array(
            $moduleCapitalised . '.' . 'Programme.index' => true,
            $moduleCapitalised . '.' . 'Programme.add' => true,
            $moduleCapitalised . '.' . 'Programme.update' => true,
            $moduleCapitalised . '.' . 'Programme.listall' => true,
            $moduleCapitalised . '.' . 'Programme.getprogrammes' => true
        );
        $agentManagerResources = array(
            $moduleCapitalised . '.' . 'Agent.index' => true,
            $moduleCapitalised . '.' . 'Agent.add' => true,
            $moduleCapitalised . '.' . 'Agent.update' => true,
            $moduleCapitalised . '.' . 'Agent.list' => true,
            $moduleCapitalised . '.' . 'Agent.getagents' => true,
            $moduleCapitalised . '.' . 'Agent.assign' => true
        );
        $studentManagerResources = array(
            $moduleCapitalised . '.' . 'Student.index' => true,
            $moduleCapitalised . '.' . 'Student.add' => true,
            $moduleCapitalised . '.' . 'Student.update' => true,
            $moduleCapitalised . '.' . 'Student.list' => true,
            $moduleCapitalised . '.' . 'Student.easyadd' => true,
            $moduleCapitalised . '.' . 'Student.feedetails' => true,
            $moduleCapitalised . '.' . 'Student.feeinstallment' => true,
        );
        $invoiceManagerResources = array(
            $moduleCapitalised . '.' . 'Invoice.index' => true
        );
        $levelAndResources = array(
            0 => $anonymousResources,
            1 => $loggedInBaseActions,
            6 => array_merge_recursive($loggedInBaseActions, $agentManagerResources, $studentManagerResources, $invoiceManagerResources),
            7 => array_merge_recursive($adminResources, $loggedInBaseActions, $instituteManagerResources, $programmeManagerResources, $agentManagerResources, $studentManagerResources, $invoiceManagerResources)
        );
        //
        $globalAcl = new Acl();
        foreach ($levelAndResources as $levelNumber => $resources) {
            $globalRole = new GenericRole($levelNumber);
            $globalAcl->addRole($globalRole);
            foreach ($resources as $resourceId => $enabled) {
                if (! $globalAcl->hasResource($resourceId))
                    $globalAcl->addResource(new GenericResource($resourceId));
            }
            
            foreach ($resources as $resourceId => $enabled) {
                $globalAcl->allow($globalRole, $resourceId);
            }
        }
        $requestedResource = $moduleCapitalised . '.' . $controller . '.' . $action;
        
        $authorised = false;
        if ($globalAcl->hasRole($userAccessLevel) && ($globalAcl->hasResource($requestedResource) && $globalAcl->isAllowed($userAccessLevel, $requestedResource))) {
            $authorised = true;
        }
        if (! $authorised) {
            if ($theEmpAuthService->hasIdentity()) {
                $errorMessage = 'You are not authorized to access this page';
                if (isset($anonymousResources[$requestedResource])) {
                    $event->setError(ERROR_NEED_AUTHENTICATED_USER);
                    $event->setParam('exception', new AuthenticationRequired($errorMessage));
                    $event->setParam('redirectUri', '/user/dashboard');
                    return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
                }
                $event->setError(RESTRICTED_ACCESS_ERROR);
                $event->setParam('identity', $theEmpAuthService->getIdentity());
                $event->setParam('controller', $controller);
                $event->setParam('action', $action);
                $event->setParam('exception', new AccessDenied($errorMessage));
                return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
            } else {
                $event->setError(ERROR_NEED_AUTHENTICATED_USER);
                $uri = $request->getUri();
                
                if (empty($uri->getQuery())) {
                    $fullUrl = $uri->getPath();
                } else {
                    $fullUrl = $uri->getPath() . '?' . $uri->getQuery();
                }
                $fullRedirectUrl = '/anonymous/signin?' . REDIRECT_PARAM_NAME . '=' . $fullUrl;
                $errorMessage = 'You must login to view this page';
                $event->setParam('exception', new AuthenticationRequired($errorMessage));
                $event->setParam('redirectUri', $fullRedirectUrl);
                return $application->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $event);
            }
        }
    }

    public function getServiceConfig()
    {
        return array(
            'initializers' => array(
                function ($instance, $sm) {
                    if ($instance instanceof UserORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                    }
                },
                function ($instance, $sm) {
                    if ($instance instanceof InstituteORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                    }
                },
                function ($instance, $sm) {
                    if ($instance instanceof ProgrammeORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                    }
                },
                function ($instance, $sm) {
                    if ($instance instanceof AgentORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                    }
                },
                function ($instance, $sm) {
                    if ($instance instanceof StudentORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                    }
                },
                function ($instance, $sm) {
                    if ($instance instanceof InvoiceORM) {
                        $instance->setOrmEntityMgr($sm->get('EvalEntityManager'));
                        $instance->setStudentModel($sm->get('StudentModel'));
                    }
                }
            ),
            'invokables' => array(
                'UserModel' => 'Application\Model\UserORM',
                'InstituteModel' => 'Application\Model\InstituteORM',
                'ProgrammeModel' => 'Application\Model\ProgrammeORM',
                'AgentModel' => 'Application\Model\AgentORM',
                'StudentModel' => 'Application\Model\StudentORM',
                'InvoiceModel' => 'Application\Model\InvoiceORM',
                'EmpAuthService' => 'Zend\Authentication\AuthenticationService'
            ),
            'aliases' => array(
                'EvalEntityManager' => 'Doctrine\ORM\EntityManager',
                'Zend\Authentication\AuthenticationService' => 'EmpAuthService'
            ),
            
            'factories' => array(
                'user_emp_eval_auth_config' => 'Application\Service\ConfigServiceFactory',
                'Application\View\RestrictedAccessHandler' => 'Application\Service\RestrictedAccessHandlerServiceFactory',
                
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
                    if (isset($config['session'])) {
                        $session = $config['session'];
                        
                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }
                        
                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }
                        
                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            $sessionSaveHandler = $sm->get($session['save_handler']);
                        }
                        
                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                        
                        if (isset($session['validators'])) {
                            $chain = $sessionManager->getValidatorChain();
                            foreach ($session['validators'] as $validator) {
                                $validator = new $validator();
                                
                                $chain->attach('session.validate', array(
                                    $validator,
                                    'isValid'
                                ));
                            }
                        }
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                }
            )
        );
    }

    public function bootstrapSession(MvcEvent $event)
    {
        $session = $event->getApplication()
            ->getServiceManager()
            ->get('Zend\Session\SessionManager');
        $session->start();
        $container = new Container(self::SESS_UQ);
        if (! isset($container->init)) {
            $serviceManager = $event->getApplication()->getServiceManager();
            $request = $serviceManager->get('Request');
            
            $session->regenerateId(true);
            $container->init = 1;
            $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
            
            $config = $serviceManager->get('Config');
            if (! isset($config['session'])) {
                return;
            }
            
            $sessionConfig = $config['session'];
            
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();
                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }
                    
                    $chain->attach('session.validate', array(
                        $validator,
                        'isValid'
                    ));
                }
            }
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
