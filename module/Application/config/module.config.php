<?php
namespace Application;

return array(
    'user_module_op_config' => array(
        'restricted_access_handler' => 'Application\View\RestrictedAccessHandler',
        'templates' => array(
            'access_denied' => 'error/accessDenied'
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    realpath(__DIR__ . '/../src/Application/Entity')
                )
            ),
            'orm_default' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\DriverChain',
                'drivers' => array(
                    'Application\Entity' => 'application_entities'
                )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'anonymous' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/anonymous',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Anonymous',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'signin' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/signin',
                            'defaults' => array(
                                'action' => 'signin'
                            )
                        )
                    ),
                    'signup' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/signup',
                            'defaults' => array(
                                'action' => 'signup'
                            )
                        )
                    )
                )
            ),
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/home',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            ),
            'base' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Index',
                        'action' => 'index'
                    )
                )
            ),
            'invoice' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/invoice',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Invoice',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'generate' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/generate/:agentId/:studentId',
                            'constraints' => array(
                                'agentId' => '[0-9]+',
                                'studentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'generate'
                            )
                        )
                    )
                )
            ),
            'student' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/student',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Student',
                        'action' => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'details' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/details/:studentId',
                            'constraints' => array(
                                'studentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'index'
                            )
                        )
                    ),
                    'easyadd' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/easyadd',
                            'defaults' => array(
                                'action' => 'easyadd'
                            )
                        )
                    ),
                    'feeinstallment' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/feeinstallment/:studentId',
                            'constraints' => array(
                                'studentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'feeinstallment'
                            )
                        )
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/update/:studentId',
                            'constraints' => array(
                                'studentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'update'
                            )
                        )
                    ),
                    'enroll' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/enroll/:instituteId/:agentId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+',
                                'agentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/list[/:instituteId[/:agentId]]',
                            'constraints' => array(
                                'instituteId' => '[0-9]+',
                                'agentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'list'
                            )
                        )
                    ),
                    'feedetails' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/feedetails[/:studentId]',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'feedetails'
                            )
                        )
                    )
                )
            ),
            'agent' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/agent',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Agent',
                        'action' => 'index'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'details' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/details/:agentId',
                            'constraints' => array(
                                'agentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'index'
                            )
                        )
                    ),
                    'assign' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/assign/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'assign'
                            )
                        )
                    ),
                    'getagents' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/getagents/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'getagents'
                            )
                        )
                    ),
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/register',
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'list' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/list',
                            'defaults' => array(
                                'action' => 'list'
                            )
                        )
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/update/:agentId',
                            'constraints' => array(
                                'agentId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'update'
                            )
                        )
                    )
                )
            ),
            'user' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'User',
                        'action' => 'index'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'signout' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/signout',
                            'defaults' => array(
                                'action' => 'signout'
                            )
                        )
                    ),
                    'dashboard' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/dashboard',
                            'defaults' => array(
                                'action' => 'dashboard'
                            )
                        )
                    )
                )
            ),
            'programme' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/programme',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Programme',
                        'action' => 'index'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'register' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/add/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'list' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/listall/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'listall'
                            )
                        )
                    ),
                    'getprogrammes' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/getprogrammes/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'getprogrammes'
                            )
                        )
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/update/:instituteId/:programmeId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+',
                                'programmeId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'update'
                            )
                        )
                    )
                )
            ),
            'institute' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/institute',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Institute',
                        'action' => 'index'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'register' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'action' => 'add'
                            )
                        )
                    ),
                    'list' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/listall',
                            'defaults' => array(
                                'action' => 'listall'
                            )
                        )
                    ),
                    'listcomponents' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/listcomponents[/:instituteId]',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'listcomponents'
                            )
                        )
                    ),
                    'details' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/details[/:instituteId]',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'details'
                            )
                        )
                    ),
                    'updatefeestructure' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/updatefeestructure/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'updatefeestructure'
                            )
                        )
                    ),
                    'deletefeecomponent' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/deletefeecomponent',
                            'constraints' => array(
                                'id' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'deletefeecomponent'
                            )
                        )
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/update/:instituteId',
                            'constraints' => array(
                                'instituteId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'update'
                            )
                        )
                    )
                )
            ),
            'admin' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Admin',
                        'action' => 'index'
                    )
                ),
                
                'may_terminate' => true,
                'child_routes' => array(
                    'adduser' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/adduser',
                            'defaults' => array(
                                'action' => 'adduser'
                            )
                        )
                    ),
                    'removeuser' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/removeuser',
                            'defaults' => array(
                                'action' => 'removeuser'
                            )
                        )
                    ),
                    'listusers' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/listusers',
                            'defaults' => array(
                                'action' => 'listusers'
                            )
                        )
                    ),
                    'updateuser' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/updateuser/:userId',
                            'constraints' => array(
                                'userId' => '[0-9]+'
                            ),
                            'defaults' => array(
                                'action' => 'updateuser'
                            )
                        )
                    ),
                    'updateusers' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/updateusers',
                            'defaults' => array(
                                'action' => 'updateusers'
                            )
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo'
            )
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => Controller\IndexController::class,
            'Application\Controller\User' => Controller\UserController::class,
            'Application\Controller\Anonymous' => Controller\AnonymousController::class,
            'Application\Controller\Admin' => Controller\AdminController::class,
            'Application\Controller\Institute' => Controller\InstituteController::class,
            'Application\Controller\Programme' => Controller\ProgrammeController::class,
            'Application\Controller\Agent' => Controller\AgentController::class,
            'Application\Controller\Student' => Controller\StudentController::class,
            'Application\Controller\Invoice' => Controller\InvoiceController::class
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml'
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array()
        )
    )
);
