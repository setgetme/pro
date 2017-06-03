<?php

namespace AppBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use AppBundle\Entity\User;

class UserEvent extends Event
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /***
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
