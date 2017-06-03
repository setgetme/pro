<?php

namespace AppBundle\Events\Listener;

use AppBundle\Events\UserEvent;
use AppBundle\Mailer\UserMailer;
use Doctrine\ORM\EntityManager;

class ActivationMailerListener
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserMailer
     */
    protected $mailer;
    /**
     * ActivationMailerListener constructor.
     *
     * @param UserMailer    $mailer
     * @param EntityManager $entityManager
     */
    public function __construct(UserMailer $mailer, EntityManager $entityManager)
    {
        $this->mailer = $mailer;
        $this->em = $entityManager;
    }

    /**
     * @param UserEvent $event
     */
    public function onRegister(UserEvent $event)
    {
        $user = $event->getUser();
        if ($user->getIsActive()) {
            return;
        }

        $user->setActionToken($this->generateActionToken());
        $this->em->persist($user);
        $this->em->flush($user);

        $this->mailer->sendActivationEmail($user);
    }

    /**
     * @return string
     */
    protected function generateActionToken()
    {
        return substr(md5(uniqid(null, true)), 0, 30);
    }
}
