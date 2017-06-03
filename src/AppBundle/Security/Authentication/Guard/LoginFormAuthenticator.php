<?php

namespace AppBundle\Security\Authentication\Guard;

use AppBundle\Exception\InactiveUserAuthenticationException;
use AppBundle\Exception\UserCannotBeEmptyAuthenticationException;
use AppBundle\Services\UserManager;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Exception\IncorrectPasswordAuthenticationException;
use AppBundle\Exception\PasswordCannotBeEmptyAuthenticationException;
use AppBundle\Exception\UserNotFoundAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * LoginFormAuthenticator constructor.
     *
     * @param EntityManager   $em
     * @param RouterInterface $router
     * @param UserManager     $userManager
     */
    public function __construct(
        UserManager $userManager,
        EntityManager $em,
        RouterInterface $router
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->userManager = $userManager;
    }
    /**
     * @param Request $request
     *
     * @return array|void
     */
    public function getCredentials(Request $request)
    {
        if ($request->get('_route') != 'user.login' || !$request->isMethod('POST')) {
            return;
        }

        return [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
        ];
    }
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        if (substr($username, 0, 1) == '@') {
            return;
        }
        $user = $this->em->getRepository('AppBundle:User')
            ->findOneByEmail($username);
        if ($user) {
            return $user;
        } else {
            throw new UserNotFoundAuthenticationException();
        }
    }
    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];
        $userName = $credentials['username'];
        if (!$userName) {
            throw new UserCannotBeEmptyAuthenticationException();
        }
        if (!$plainPassword) {
            throw new PasswordCannotBeEmptyAuthenticationException();
        }
        if (!$this->userManager->getEncoder($user)->isPasswordValid(
            $user->getPassword(),
            $plainPassword,
            $user->getSalt()
        )
        ) {
            throw new IncorrectPasswordAuthenticationException();
        }
        if ($user->getIsActive() !== true) {
            throw new InactiveUserAuthenticationException();
        }

        return true;
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->getSession() instanceof SessionInterface) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->router->generate('user.login'));
    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->router->generate('homepage'));
    }
    public function supportsRememberMe()
    {
        return true;
    }
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $url = $this->router->generate('user.login');

        return new RedirectResponse($url);
    }
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return parent::createAuthenticatedToken($user, $providerKey);
    }
}
