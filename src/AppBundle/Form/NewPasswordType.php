<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'required' => true,
                'invalid_message' => 'user.new_password.must_match',
                'first_options' => ['label' => 'user.new_password.password_first'],
                'second_options' => ['label' => 'user.new_password.password_repeat'],
                'options' => array('attr' => ['class' => 'password-field']),
                'type' => PasswordType::class,
            ])
            ->add('save', SubmitType::class, ['label' => 'user.new_password.save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'validation_groups' => ['new_password'],
        ]);
    }
}
