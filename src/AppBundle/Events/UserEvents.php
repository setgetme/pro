<?php

namespace AppBundle\Events;

class UserEvents
{
    const REGISTERED = 'user.registered';
    const ACTIVATION = 'user.activation';
    const REMIND_PASSWORD = 'remind.password';
}
