<?php
namespace App\Form;

use App\Entity\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

/**
 * User Type (Registration) Form
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultAttributes = array(
            'label_attr' => array('class' => 'sr-only')
        );

        $builder
            ->add('_username', EmailType::class, $defaultAttributes)
            ->add('_password', PasswordType::class, $defaultAttributes)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['intention' => 'authentication']);
    }
}
