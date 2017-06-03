<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Events\UserEvent;
use AppBundle\Events\UserEvents;
use AppBundle\Form\AvatarType;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Form\EditUserType;
use AppBundle\Form\NewPasswordType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\RemindPasswordType;
use AppBundle\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/user/create", name="user.create")
     * @Template
     */
    public function createUser(Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('manager.user')->create($user);
            $this->addFlash('notice', $this->get('translator')->trans('user.form.create'));

            $event = new UserEvent($user);
            $this->get('event_dispatcher')->dispatch(UserEvents::REGISTERED, $event);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/user/edit", name="user.edit")
     * @Template
     */
    public function editUser(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        $form = $this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()) {
            $this->get('manager.user')->edit($user);
            $this->addFlash('notice', $this->get('translator')->trans('user.form.edit.success'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/user/change-password", name="user.change_password")
     * @Template
     */
    public function changePassword(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, new ChangePassword());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $changePassword = $form->getData();
            $user->setPlainPassword($changePassword->getNewPassword());
            $this->get('manager.user')->updatePassword($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', $this->get('translator')->trans('user.form.change_password.success'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/accout-activation/{token}", name="user.activation_account")
     * @Template
     */
    public function activationAccount(Request $request, $token)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['actionToken' => $token]);

        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $this->get('manager.user')->activate($user);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash('notice', $this->get('translator')->trans('user.activate_account'));

        return [];
    }

    /**
     *@Route("user/remind-password", name="user.remind_password")
     *@Template
     */
    public function remindPassword(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(RemindPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findOneBy(['email' => $form['email']->getData()]);
            if ($user) {
                if ($user->getisActive() == false) {
                    $this->addFlash('notice', $this->get('translator')->trans('user.form.remind_password.user_inactive'));

                    return $this->redirectToRoute('user.remind_password');
                }
                $this->get('manager.user')->sendRemindPasswordUrl($user);
            }
            $this->addFlash('notice', $this->get('translator')->trans('user.form.remind_password.send_link_if_email_exist'));

            return $this->redirectToRoute('user.remind_password');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/remind-password/{token}", name="user.set_new_password")
     * @Template
     */
    public function setNewPassword(Request $request, $token)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['actionToken' => $token]);

        if (!$user) {
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->get('manager.user')->create($user);
            $this->addFlash('notice', $this->get('translator')->trans('user.form.new_password'));

            return $this->redirectToRoute('homepage');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("user/login", name="user.login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'login/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }
}
