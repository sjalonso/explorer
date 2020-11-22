<?php

namespace App\Form;

use App\Entity\Paquete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaqueteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('aerolineas')

       
        
           ->add('fechaida', DateType::class, [
            'label'=>'invoice_date',
            'widget'=>'single_text',
            'required'=>true,
            
        ])

            ->add('fecharegreso', DateType::class, [
                'label'=>'invoice_date',
            'widget'=>'single_text',
            'required'=>true,

                
            ]
            )
            ->add('fechalimite', DateType::class, [
                'label'=>'invoice_date',
            'widget'=>'single_text',
            'required'=>true,

                
            ]
            )
            ->add('cantasiento')
            ->add('cantasientooriginal')
            ->add('preciocuba')
            ->add('preciodestino')
            ->add('preciodestinodoble')
            ->add('preciodestinotriple')
            ->add('preciodestinocuadruple')
            ->add('preciominisimple')
            ->add('preciominidoble')
            ->add('preciominitriple')
            ->add('preciominicuadruple')
            ->add('preciominininno')
            ->add('precioinfante')
            ->add('precioinfantedestino')
            ->add('preciomininvsimple')
            ->add('preciomininvdoble')
            ->add('preciomininvtriple')
            ->add('preciomininvcuadruple')
            ->add('preciomininvinfante')
            
            ->add('Ruta')
            ->add('EstadoPaquete')
            ->add('codigopaquete', TextType::class, ['label' => 'Código del Paquete'])
            ->add('descripcion', TextareaType::class, ['label' => 'Descripción','required'   => false])
        ;
        $builder->get('fechaida')->addModelTransformer(new CallbackTransformer(
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

        $builder->get('fechalimite')->addModelTransformer(new CallbackTransformer(
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
            'data_class' => Paquete::class,
        ]);
    }
}
