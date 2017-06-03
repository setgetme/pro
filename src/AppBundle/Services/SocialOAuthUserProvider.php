<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUserProvider;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Exception\InactiveUserAuthenticationException;
use AppBundle\Exception\SocialAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use HWI\Bundle\OAuthBundle\Connect\AccountConnectorInterface;

class SocialOAuthUserProvider extends OAuthUserProvider implements AccountConnectorInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * SocialOAuthUserProvider constructor.
     *
     * @param EntityManager $em
     * @param UserManager   $userManager
     */
    public function __construct(
        EntityManager $em,
        UserManager $userManager
    ) {
        $this->em = $em;
        $this->userManager = $userManager;
    }

    /**
     * @param UserResponseInterface $response
     *
     * @return UserInterface
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $email = $response->getEmail();
        $fbId =  $response->getUsername();
        if (!$email) {
            throw new SocialAuthenticationException('social_login.email.notblank');
        }
        $fullName = explode(' ', $response->getResponse()['name']);
        /** @var User $user */
        $user = $this->em->getRepository('AppBundle:User')->findOneByEmail($email);
        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setFacebookId($fbId);
            $user->setPassword(substr(md5(uniqid(null, true)), 0, 30));
            $user->setFirstName($fullName[0]);
            $user->setLastName($fullName[1]);
            $user->setPlainPassword('');
            $user->setIsActive(true);
            $user->setTermsAcceptedAt(new \DateTime());
            $this->userManager->updatePassword($user);
            if ($response->getProfilePicture()) {
                $avatar = file_get_contents('http://graph.facebook.com/'.$fbId.'/picture?width=150&height=150');
                $this->userManager->saveAvatar($user, $avatar);
            }
            $this->em->persist($user);
            $this->em->flush();
        }
        if ($user->getIsActive() !== true) {
            throw new InactiveUserAuthenticationException();
        }

        return $user;
    }


    /**
     * Connects the response to the user object.
     *
     * @param UserInterface         $user     The user object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
    }
}
