<?php

namespace App\Form;

use App\Entity\Areopuerto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreopuertoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('siglas', TextType::class, ['label' => 'Siglas'])
            ->add('pais', TextType::class, ['label' => 'País'])
            ->add('provincia', TextType::class, ['label' => 'Provincia'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Areopuerto::class,
        ]);
    }
}
