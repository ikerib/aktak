<?php

namespace App\Form;

use App\Entity\Liburua;
use App\Entity\Mota;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Egoera;

class LiburuaSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('kodea', TextType::class, [
                'required' => false,
                'attr'=>['autocomplete' => 'off']
            ])
            ->add('hasieratik', DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-4'
                ],
                'html5' => false,
                'label' => 'Hasiera-tik',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('hasierara', DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-4'
                ],
                'html5' => false,
                'label' => 'Hasiera-ra',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('amaieratik', DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-3'
                ],
                'html5' => false,
                'label' => 'Amaiaera-tik',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('amaierara', DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-3'
                ],
                'html5' => false,
                'label' => 'Amaiera-ra',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('egoera', EntityType::class, [
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Egoera::class,
                'placeholder' => 'Aukeratu bat',
                'required' => false
            ])
            ->add('mota', EntityType::class, [
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Mota::class,
                'placeholder' => 'Aukeratu bat',
                'required' => false
            ])
            ->add('Bilatu', SubmitType::class, [
                'attr' => ['class'=>'btn btn-primary']
            ])
            ->add('Garbitu', ButtonType::class,[
                'label' => 'Garbitu',
                'attr'  => ['class' => 'btn btn-warning cmdGarbituLiburuaSearchForm']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Liburua::class,
        ]);
    }
}
