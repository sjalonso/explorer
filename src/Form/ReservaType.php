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

class ReservaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sucursal = $options['sucursal'];
        $usuario = $options['usuario'];
        if($sucursal && !$usuario)
            $builder
                ->add('fechacreacion', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('EstadoReserva')
                ->add('descripcion', TextareaType::class, ['label' => 'Nota','required'   => false])
                ->add('Cliente', null, array('query_builder' => function(EntityRepository $er) use ($sucursal){
                    return $er->createQueryBuilder("cliente")
                        ->where("cliente.sucursal = :sucursal")
                        ->setParameter("sucursal", $sucursal)
                        ;
                }))
                ->add('hospedaje')
                ->add('infante', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('NO' => false, 'SI' => true), 'label' => 'Infante'))

                ->add('tipopaquete', ChoiceType::class, array(

                    'choices' => array(
                        'Paquete Normal' => 'Paquete Normal',
                        'Mini paquete con visa' => 'Mini paquete con visa',
                        'Mini paquete sin visa' => 'Mini paquete sin visa',

                    ),
                ), ['required'=>true])

            ;
        else if($sucursal && $usuario)
            $builder
                ->add('fechacreacion', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,

                ])
                ->add('EstadoReserva')
                ->add('descripcion', TextareaType::class, ['label' => 'Nota','required'   => false])
                ->add('Cliente', null, array('query_builder' => function(EntityRepository $er) use ($sucursal, $usuario){
                    return $er->createQueryBuilder("cliente")
                        ->where("cliente.sucursal = :sucursal AND cliente.user = :user")
                        ->setParameter("sucursal", $sucursal)
                        ->setParameter("user", $usuario)
                        ;
                }))
                ->add('hospedaje')
                ->add('infante', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('NO' => false, 'SI' => true), 'label' => 'Infante'))

                ->add('tipopaquete', ChoiceType::class, array(

                    'choices' => array(
                        'Paquete Normal' => 'Paquete Normal',
                        'Mini paquete con visa' => 'Mini paquete con visa',
                        'Mini paquete sin visa' => 'Mini paquete sin visa',

                    ),
                ), ['required'=>true])

            ;

        else
            $builder
                ->add('fechacreacion', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('EstadoReserva')
                ->add('Cliente')
                ->add('hospedaje')
                ->add('descripcion', TextareaType::class, ['label' => 'Nota','required'   => false])
                ->add('infante', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('NO' => false, 'SI' => true), 'label' => 'Infante'))
                   
                    ->add('tipopaquete', ChoiceType::class, array(
                        
                        'choices' => array(
                             'Paquete Normal' => 'Paquete Normal',
                             'Mini paquete con visa' => 'Mini paquete con visa',
                             'Mini paquete sin visa' => 'Mini paquete sin visa',
                             
                         ),
                     ), ['required'=>true])  
                   


            ;

            $builder->get('fechacreacion')->addModelTransformer(new CallbackTransformer(
                function ($value) {
                    if(!$value) {
                        return new \DateTime('now');
                    }
                    return $value;
                },
                function ($value) {
                    return $value;
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reserva::class,
            'sucursal' => null,
            'usuario' => null
        ]);
    }
}
