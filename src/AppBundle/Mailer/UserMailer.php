<?php

namespace AppBundle\Mailer;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface as Templating;
use Symfony\Component\Translation\TranslatorInterface;

class UserMailer
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    private $fromEmail;

    private $fromName;

    /**
     * @var Templating
     */
    protected $templating;

    protected $activationUrl;

    protected $remindPasswordUrl;

    protected $url;

    /**
     * UserMailer constructor.
     *
     * @param TranslatorInterface $translator
     * @param Templating          $templating
     * @param \Swift_Mailer       $swiftMailer
     * @param $fromEmail
     * @param $fromName
     * @param $activationUrl
     * @param $remindPasswordUrl
     * @param $url
     */
    public function __construct(
        TranslatorInterface $translator,
        Templating $templating,
        \Swift_Mailer $swiftMailer,
        $fromEmail,
        $fromName,
        $activationUrl,
        $remindPasswordUrl,
        $url
    ) {
        $this->translator = $translator;
        $this->templating = $templating;
        $this->swiftMailer = $swiftMailer;
        $this->fromEmail = $fromEmail;
        $this->fromName = $fromName;
        $this->activationUrl = $activationUrl;
        $this->remindPasswordUrl = $remindPasswordUrl;
        $this->url = $url;
    }

    public function send($email, $name, $subject, $htmlBody)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->fromEmail, $this->fromName)
            ->setTo($email, $name)
            ->setBody($htmlBody, 'text/html');

        $this->swiftMailer->send($message);
    }

    public function sendActivationEmail(User $user)
    {
        $activationUrl = $this->url.$this->activationUrl.$user->getActionToken();

        $emailBody = $this->templating->render('AppBundle:Email:accountActivation.html.twig',
            ['firstName' => $user->getFirstName(), 'activationUrl' => $activationUrl]
        );

        $this->send(
            $user->getEmail(),
            $user->getFirstName(),
            $this->translator->trans('user.email.activation.title'),
            $emailBody
        );

        return true;
    }

    public function sendRemindPasswordEmail(User $user)
    {
        $remindPasswordUrl = $this->url.$this->remindPasswordUrl.$user->getActionToken();

        $emailBody = $this->templating->render(
            'AppBundle:Email:remindPassword.html.twig',
            ['remindPasswordUrl' => $remindPasswordUrl]
        );

        $this->send(
            $user->getEmail(),
            $user->getFirstName(),
            $this->translator->trans('user.email.remind_password'),
            $emailBody
        );

        return true;
    }
}
