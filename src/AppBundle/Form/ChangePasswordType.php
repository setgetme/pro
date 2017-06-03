<?php

namespace AppBundle\Form;

use AppBundle\Model\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', RepeatedType::class, [
                'required' => true,
                'invalid_message' => 'user.new_password.password.must_match',
                'first_options' => ['label' => 'user.new_password.password_first'],
                'second_options' => ['label' => 'user.new_password.password_repeat'],
                'options' => array('attr' => ['class' => 'password-field']),
                'type' => PasswordType::class,
            ])
            ->add('newPassword', PasswordType::class, ['required' => true, 'label' => 'user.new_password.new_password'])
            ->add('save', SubmitType::class, ['label' => 'user.new_password.save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePassword::class,
            'validation_groups' => ['change_password'],
        ]);
    }
}
