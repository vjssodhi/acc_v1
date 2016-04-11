<?php
namespace Application\View;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Application;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;
use Zend\View\Model\ViewModel;
use Application\Exception\AccessDenied;

class RestrictedAccessHandler implements ListenerAggregateInterface
{

    /**
     *
     * @var string
     */
    protected $template;

    /**
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     *
     * @param string $template
     *            name of the template to use on unauthorized requests
     */
    public function __construct($template)
    {
        $this->template = (string) $template;
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, 
            array(
                $this,
                'errorHandler'
            ), - 5000);
    }

    /**
     *
     * {@inheritDoc}
     *
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     *
     * @param string $template            
     */
    public function setTemplate($template)
    {
        $this->template = (string) $template;
    }

    /**
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     *
     * @param MvcEvent $event            
     *
     * @return void
     */
    public function errorHandler(MvcEvent $event)
    {
        
        /**
         */
        $result = $event->getResult();
        $response = $event->getResponse();
        
        if ($result instanceof Response || ($response && ! $response instanceof HttpResponse)) {
            return;
        }
        
        // Common view variables
        $viewVariables = array(
            'error' => $event->getParam('error'),
            'identity' => $event->getParam('identity')
        );
        $error = $event->getError();
        
        switch ($error) {
            case RESTRICTED_ACCESS_ERROR:
                $viewVariables['controller'] = $event->getParam('controller');
                $viewVariables['action'] = $event->getParam('action');
                $viewVariables['reason'] = $event->getParam('exception')->getMessage();
                break;
            case Application::ERROR_EXCEPTION:
                if (! ($event->getParam('exception') instanceof AccessDenied)) {
                    return;
                }
                
                $viewVariables['reason'] = $event->getParam('exception')->getMessage();
                $viewVariables['error'] = 'error-unauthorized';
                break;
            default:
                return;
        }
        $vm = new ViewModel(array(
            'error_data' => $viewVariables
        ));
        $response = $response ?: new HttpResponse();
        if (empty($event->getParam('template'))) {
            $vm->setTemplate($this->getTemplate());
        } else {
            $vm->setTemplate($event->getParam('template'));
        }
        
        $event->getViewModel()->addChild($vm);
        $response->setStatusCode(403);
        $event->setResponse($response);
        return $response;
    }
}
