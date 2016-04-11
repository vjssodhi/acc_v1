<?php
namespace Application\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\View\RestrictedAccessHandler;

class RestrictedAccessHandlerServiceFactory implements FactoryInterface
{

    /**
     *
     * {@inheritDoc}
     *
     * @return \Application\View\RestrictedAccessHandler
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('user_emp_eval_auth_config');
        return new RestrictedAccessHandler($config['templates']['access_denied']);
    }
}