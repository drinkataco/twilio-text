<?php
namespace App\Form;

use App\Entity\Message;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Message Form
 *
 * @author Josh Walwyn <me@joshwalwyn.com>
 */
class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $defaultAttributes = array(
            'label_attr' => array('class' => 'sr-only')
        );

        $builder->add('recipient', TextType::class, $defaultAttributes)
            ->add(
                'messageBody',
                TextareaType::class,
                array_merge($defaultAttributes, array(
                    'attr' => array(
                        'class' => 'form-control message-form__message mb-2',
                        'data-bind-counter-max' => Message::MESSAGEBODY_MAX,
                        // Allow more for JS warning. Only defined amount will be allowed though
                        'maxLength' => (Message::MESSAGEBODY_MAX * 1.5)
                    )
                ))
            );
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Message::class,
        ));
    }
}
