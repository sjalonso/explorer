<?php

namespace App\Form;

use App\Entity\Reserva;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservaType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sucursal = $options['sucursal'];
        if($sucursal)
            $builder
                ->add('EstadoReserva')
            ;

        else
            $builder
                ->add('EstadoReserva')
            ;

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
            'sucursal' => null
        ]);
    }
}
