<?php

namespace AppBundle\Events\Listener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Translation\TranslatorInterface;

class LogoutListener implements LogoutSuccessHandlerInterface
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * LogoutListener constructor.
     *
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }
    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->session->invalidate();
        $this->session->getFlashBag()->add('notice', $this->translator->trans('user.logout'));
        $response = new RedirectResponse('/');

        return $response;
    }
}
