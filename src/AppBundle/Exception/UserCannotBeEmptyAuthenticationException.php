<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class UserCannotBeEmptyAuthenticationException.
 */
class UserCannotBeEmptyAuthenticationException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'user.exception.authentication.email_notblank';
    }
}
