<?php

namespace App\Form;


use App\Filter\ChambreFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ChambreFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('from', DateType::class,[
                'label' => 'Du :',
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "La date d'entrer est obligatoire"
                    ])
                ]
            ])
            ->add('to', DateType::class,[
                'label' => 'Au :',
                'required' => false,
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => "La date de sortir est obligatoire"
                    ])
                ]
            ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChambreFilter::class,
        ]);
    }
}
