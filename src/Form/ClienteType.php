<?php

namespace App\Form;

use App\Entity\Cliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ClienteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('apellido', TextType::class, ['label' => 'Apellidos'])
            ->add('fechanacimiento', DateType::class, ['years' => range(date('Y')-100,date('Y')),'label' => 'Fecha nacimiento'])
            ->add('pasaporte', TextType::class, ['label' => 'Pasaporte'])
            ->add('lugarexpedicion', TextType::class, ['label' => 'Lugar de Expedición'])

            ->add('fechaexpiracion', DateType::class, [ 
                 'label'=>'invoice_date',
                 'widget'=>'single_text',
                 'required'=>true,
            ])
            
            ->add('fechaexpedicion', DateType::class, [ 
                 'label'=>'invoice_date',
                 'widget'=>'single_text',
                 'required'=>true,])
            
            ->add('telefono',  TextType::class, ['label' => 'Teléfono','required'   => false])
            ->add('correo',  TextType::class,['label' => 'Teléfono','required'   => false] )
            ->add('lugarnacimiento', TextType::class, ['label' => 'Lugar de Nacimiento'])
            ->add('sexo')
            ->add('titulo')
            ->add('nacionalidad', TextType::class, ['label' => 'Nacionalidad'])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cliente::class,
        ]);
    }
}
