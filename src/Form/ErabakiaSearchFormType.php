<?php

namespace App\Form;

use App\Entity\Erabakia;
use App\Entity\Liburua;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ErabakiaSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('testua', TextType::class, [
                'attr' => [
                    'autocomplete' => 'off'
                ],
                'label' => 'form.testua',
                'mapped' => false,
                'required' => false
            ])
            ->add('datatik',DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-4'
                ],
                'html5' => false,
                'label' => 'form.hasiera',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('datara',DateType::class, [
                'attr' => [
                    'class' => 'form-control input-inline datepicker col-4'
                ],
                'html5' => false,
                'label' => 'form.amaiera',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('liburua', EntityType::class, [
                'attr' => [
                    'class' => 'col-6'
                ],
                'class' => Liburua::class,
                'placeholder' => 'form.aukeratu',
                'label' => 'form.liburua',
                'required' => false
            ])
            ->add('Bilatu', SubmitType::class, [
                'attr' => ['class'=>'btn btn-primary'],
                'label' => 'form.bilatu',
            ])
            ->add('Garbitu', ButtonType::class,[
                'label' => 'form.garbitu',
                'attr'  => ['class' => 'btn btn-warning cmdGarbituErabakiaSearchForm']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Erabakia::class,
        ]);
    }
}
