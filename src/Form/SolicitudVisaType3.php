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

class SolicitudVisaType3 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sucursal = $options['sucursal'];
        if($sucursal)
            $builder
                
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

         
      
            
    }

    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SolicitudVisa::class,
            'sucursal' => null
        ]);
    }
}