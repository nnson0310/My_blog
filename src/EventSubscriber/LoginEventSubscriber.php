<?php

namespace App\EventSubscriber;

use App\Middleware\LoginAuthenticated;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginEventSubscriber implements EventSubscriberInterface
{

  protected $requestStack;

  protected $urlGenerator;

  public function __construct(RequestStack $requestStack, UrlGeneratorInterface $urlGenerator)
  {
    $this->requestStack = $requestStack;
    $this->urlGenerator = $urlGenerator;
  }

  public function onLoginAuthentication (ControllerEvent $event)
  {
    $controller = $event->getController();

    if (is_array($controller)) {
      $controller = $controller[0];
    }

    if ($controller instanceof LoginAuthenticated) {
      $session = $this->requestStack->getSession();
      $username = $session->get('username');
      if (!$username) {
        throw new AccessDeniedException();
      }
    }
  }

  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::CONTROLLER => 'onLoginAuthentication'
    ];
  }
}
