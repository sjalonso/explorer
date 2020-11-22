<?php

namespace App\Form;

use App\Entity\SolicitudVisa;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SolicitudVisaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sucursal = $options['sucursal'];
        $usuario = $options['usuario'];
        if($sucursal && !$usuario)
            $builder
                ->add('direccioncuba')
                ->add('direccionextranjero')
                ->add('pais')
                ->add('fechasolicitud', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])

                ->add('fechasalidaviaje', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                
                ->add('ocupacion')
                ->add('lugartrabajo')
                ->add('duracionestancia')
                ->add('motivo')

                ->add('fecharegreso', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('direccionhaiti')
                ->add('viajehaiti', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('SI' => true, 'NO' => false), 'label' => 'Viaje haití'))
                ->add('Cliente', null, array('query_builder' => function(EntityRepository $er) use ($sucursal){
                    return $er->createQueryBuilder("cliente")
                        ->where("cliente.sucursal = :sucursal")
                        ->setParameter("sucursal", $sucursal)
                        ;
                }))
                ->add('Estadocivil')
                ->add('EstadoSolicitudvisa')
                ->add('fechainiciotramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('fechafinaltramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
              

        ;
        if($sucursal && $usuario)
            $builder
                ->add('direccioncuba')
                ->add('direccionextranjero')
                ->add('pais')
                ->add('fechasolicitud', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,

                ])

                ->add('fechasalidaviaje', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])

                ->add('ocupacion')
                ->add('lugartrabajo')
                ->add('duracionestancia')
                ->add('motivo')

                ->add('fecharegreso', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,

                ])
                ->add('direccionhaiti')
                ->add('viajehaiti', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('SI' => true, 'NO' => false), 'label' => 'Viaje haití'))
                ->add('Cliente', null, array('query_builder' => function(EntityRepository $er) use ($sucursal, $usuario){
                    return $er->createQueryBuilder("cliente")
                        ->where("cliente.sucursal = :sucursal AND cliente.user = :usuario")
                        ->setParameter("sucursal", $sucursal)
                        ->setParameter("usuario", $usuario)
                        ;
                }))
                ->add('Estadocivil')
                ->add('EstadoSolicitudvisa')
                ->add('fechainiciotramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,

                ])
                ->add('fechafinaltramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,

                ])


            ;
        else
            $builder
                ->add('direccioncuba')
                ->add('direccionextranjero')
                ->add('pais')
                ->add('fechasolicitud', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])

                ->add('fechasalidaviaje', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
               
                ->add('ocupacion')
                ->add('lugartrabajo')
                ->add('duracionestancia')
                ->add('motivo')
                ->add('fecharegreso', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('direccionhaiti')
                ->add('viajehaiti', ChoiceType::class, array('multiple' => false,'expanded' => false,
                    'choices' => array('SI' => true, 'NO' => false), 'label' => 'Viaje haití'))
                ->add('Cliente')
                ->add('Estadocivil')
               ->add('EstadoSolicitudvisa' )
                ->add('fechainiciotramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
                ->add('fechafinaltramite', DateType::class, [
                    'label'=>'invoice_date',
                    'widget'=>'single_text',
                    'required'=>true,
                  
                ])
               
            ;

         
            $builder->get('fechasolicitud')->addModelTransformer(new CallbackTransformer(
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

        $builder->get('fecharegreso')->addModelTransformer(new CallbackTransformer(
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

        $builder->get('fechasalidaviaje')->addModelTransformer(new CallbackTransformer(
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
            'data_class' => SolicitudVisa::class,
            'sucursal' => null,
            'usuario' => null
        ]);
    }
}
