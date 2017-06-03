<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Address;

/**
 * Class AddressType.
 */
class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('postCode', TextType::class, ['label' => "user.create_account.post_code"])
            ->add('address', TextType::class, ['label' => "user.create_account.address"]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Address::class]);
    }
}
