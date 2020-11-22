<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder            
//            ->add('lastpassword', PasswordType::class, array('label' => '* Contraseña anterior', 'attr' => array('class' => 'form-control')))
            ->add('plainpassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'invalid_message' => 'Debe coincidir el valor de la contraseña',
                'first_options' => array('label' => '* Contraseña nueva:', 'attr' => array('class' => 'form-control')),
                'second_options' => array('label' => '* Repita Contraseña:', 'attr' => array('class' => 'form-control'))
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
