<?php

namespace App\Form;

use App\Entity\Liburua;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LiburuaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('kodea')
            ->add('hasiera', DateType::class, [
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('amaiera', DateType::class, [
                'widget' => 'single_text',
                'html5' => false
            ])
            ->add('orriak')
            ->add('altuera')
            ->add('zabalera')
            ->add('katalogatua', null, [
              'label' => ''
            ])
            ->add( 'oharrak', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('observaciones', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('egoera')
            ->add('mota')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Liburua::class,
        ]);
    }
}
