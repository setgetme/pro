<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class IncorrectPasswordAuthenticationException.
 */
class IncorrectPasswordAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'user.exception.authentication.incorrect_password';
    }
}
