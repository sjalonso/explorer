<?php

namespace App\Form;

use App\Entity\EstadoSolicitudvisa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EstadoSolicitudvisaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoestado', TextType::class, ['label' => 'Estado de Solicitud de Visa'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EstadoSolicitudvisa::class,
        ]);
    }
}
