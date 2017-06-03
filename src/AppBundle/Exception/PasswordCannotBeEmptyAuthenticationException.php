<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class PasswordCannotBeEmptyAuthenticationException.
 */
class PasswordCannotBeEmptyAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'user.exception.authentication.password_notblank';
    }
}
