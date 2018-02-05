<?php
namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * User Type (Registration) Form
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultAttributes = array(
            'label_attr' => array('class' => 'sr-only')
        );

        $builder
            ->add('name', TextType::class, $defaultAttributes)
            ->add('email', EmailType::class, $defaultAttributes)
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array_merge(array('label' => 'Password'), $defaultAttributes),
                'second_options' => array_merge(array('label' => 'Repeat Password'), $defaultAttributes),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
