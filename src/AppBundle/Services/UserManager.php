<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use AppBundle\Events\UserEvent;
use AppBundle\Events\UserEvents;

/**
 * Class UserManager.
 */
class UserManager
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * UserManager constructor.
     * @param EncoderFactoryInterface $encoderFactory
     * @param EntityManager $em
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EntityManager $em,
        EventDispatcherInterface $dispatcher
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
    }

    public function create(User $user)
    {
        $this->updatePassword($user);
        $this->em->persist($user);
        $this->em->flush($user);
    }

    public function edit(User $user)
    {
        $this->em->persist($user);
        $this->em->flush($user);
    }

    public function updatePassword(User $user)
    {
        if (0 !== strlen($password = $user->getPlainPassword())) {
            $encoder = $this->getEncoder($user);
            $user->setPassword($encoder->encodePassword($password, $user->getSalt()));
            $user->eraseCredentials();
        }
    }

    public function getEncoder($object)
    {
        return $this->encoderFactory->getEncoder($object);
    }

    public function activate(User $user)
    {
        $user->setIsActive(true);
        $user->setActionToken(null);
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::ACTIVATION, $event);
        $this->em->persist($user);

        return true;
    }

    public function sendRemindPasswordUrl(User $user)
    {
        $event = new UserEvent($user);
        $this->dispatcher->dispatch(UserEvents::REMIND_PASSWORD, $event);
    }

    public function saveAvatar(User $user, $avatar)
    {
        $file = $user->getUploadRootDir().$user->getFacebookId().'.jpg';
        file_put_contents($file, $avatar);
        $user->setAvatar($user->getFacebookId().'.jpg');
    }
}
