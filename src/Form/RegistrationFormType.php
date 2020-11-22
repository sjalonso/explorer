<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\Arraytype;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', null, ['required'=>true, 'label'=>'Correo'])
//            ->add('plainPassword', PasswordType::class, [
//                'label' => 'Contraseña',
//                // instead of being set onto the object directly,
//                // this is read and encoded in the controller
//                'mapped' => false,
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Entre una contraseña',
//                    ]),
//                    new Length([
//                        'min' => 6,
//                        'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres',
//                        // max length allowed by Symfony for security reasons
//                        'max' => 4096,
//                    ]),
//                ],
//            ])
            ->add('Sucursal')
            ->add('roles', ChoiceType::class, array(
                'multiple' => true,
                'expanded' => true,
                'choices' => array(
                     'Comercial' => 'ROLE_USER',
                     'Admin' => 'ROLE_ADMIN',
                     'Visado' => 'ROLE_VISADO',
                     'Jefe de Sucursal' => 'ROLE_SUCURSAL',
                     'Receptor' => 'ROLE_RECEPTOR',
                     'Aerolínea' => 'ROLE_AEROLINEA',
                     'Superadmin' => 'ROLE_SUPERADMIN',
                 ),
             ), ['required'=>true])

            ->add('nombre')
            ->add('activo', ChoiceType::class, array('multiple' => false,'expanded' => false,
                'choices' => array('SI' => 'si', 'NO' => 'no'), 'label' => 'Activo'))
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
