<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['required' => true, 'label' => 'user.edit_account.first_name'])
            ->add('lastName', TextType::class, ['required' => true, 'label' => 'user.edit_account.last_name'])
            ->add('city', TextType::class, ['required' => true, 'label' => 'user.edit_account.city'])
            ->add('address', AddressType::class, ['required' => true, 'label' => false])
            ->add('phoneNumber', TextType::class, ['required' => true, 'label' => 'user.edit_account.phone_number'])
            ->add('avatarFile', FileType::class, ['required' => false, 'label' => 'user.edit_account.avatar'])
            ->add('save', SubmitType::class, ['label' => 'user.edit_account.save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
