<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class SocialAuthenticationException.
 */
class SocialAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'user.exception.authentication.social_email_notblank';
    }
}
