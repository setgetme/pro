<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Form\DataTransformer\BooleanToDateTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['required' => true, 'label' => 'user.create_account.email'])
            ->add('plainPassword', RepeatedType::class, [
                'required' => true,
                'invalid_message' => 'user.create_account.password.must_match',
                'first_options' => ['label' => 'user.create_account.password_first'],
                'second_options' => ['label' => 'user.create_account.password_repeat'],
                'options' => array('attr' => ['class' => 'password-field']),
                'type' => PasswordType::class,
            ])
            ->add('firstName', TextType::class, ['required' => true, 'label' => 'user.create_account.first_name'])
            ->add('lastName', TextType::class, ['required' => true, 'label' => 'user.create_account.last_name'])
            ->add('city', TextType::class, ['required' => true, 'label' => 'user.create_account.city'])
            ->add('address', AddressType::class, ['required' => true, 'label' => false])
            ->add('phoneNumber', TextType::class, ['required' => true, 'label' => 'user.create_account.phone_number'])
            ->add('termsAcceptedAt', CheckboxType::class, ['required' => true, 'label' => 'user.create_account.terms_accepted_at'])
            ->add('save', SubmitType::class, ['label' => 'user.create_account.registration']);

        $builder->get('termsAcceptedAt')
            ->addModelTransformer(new BooleanToDateTransformer());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['registration'],
        ]);
    }
}
