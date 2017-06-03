<?php

namespace AppBundle\Events\Listener;

use AppBundle\Events\UserEvent;
use AppBundle\Mailer\UserMailer;
use Doctrine\ORM\EntityManager;

class RemindPasswordMailerListener
{
    /**
     * @var UserMailer
     */
    protected $mailer;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * RemindPasswordMailerListener constructor.
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
    public function onRemindPassword(UserEvent $event)
    {
        $user = $event->getUser();

        $user->setActionToken($this->generateActionToken());
        $this->em->persist($user);
        $this->em->flush($user);

        $this->mailer->sendRemindPasswordEmail($user);
    }

    /**
     * @return string
     */
    protected function generateActionToken()
    {
        return substr(md5(uniqid(null, true)), 0, 30);
    }
}
