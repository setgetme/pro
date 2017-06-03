<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class InactiveUserAuthenticationException.
 */
class InactiveUserAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'user.exception.authentication.inactive_user';
    }
}
