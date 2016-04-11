<?php
namespace Application\View;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Application\Exception\AuthenticationRequired;

class RedirectionHandler implements ListenerAggregateInterface
{

    protected $redirectRoute = 'home';

    protected $redirectUri;

    /**
     *
     * @var \Zend\Stdlib\CallbackHandler[]
     *
     */
    protected $listeners = array();

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
                'onDispatchError'
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
     * @param \Zend\Mvc\MvcEvent $event            
     *
     */
    public function onDispatchError(MvcEvent $event)
    {
        $result = $event->getResult();
        $routeMatch = $event->getRouteMatch();
        $response = $event->getResponse();
        $router = $event->getRouter();
        $error = $event->getError();
        $url = $event->getParam('redirectUri');
        if ($result instanceof Response || ! $routeMatch ||
             ($response && ! $response instanceof Response) ||
             ! ((ERROR_NEED_AUTHENTICATED_USER === $error &&
             ($event->getParam('exception') instanceof AuthenticationRequired)))) {
            return;
        }
        if (null === $url) {
            $url = $router->assemble(array(), 
                array(
                    'name' => $this->redirectRoute
                ));
        }
        $response = $response ?: new Response();
        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);
        $event->setResponse($response);
        
        return $response;
    }

    /**
     *
     * @param string $redirectRoute            
     *
     */
    public function setRedirectRoute($redirectRoute)
    {
        $this->redirectRoute = (string) $redirectRoute;
    }

    /**
     *
     * @param string|null $redirectUri            
     *
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri ? (string) $redirectUri : null;
    }
}