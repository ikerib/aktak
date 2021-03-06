<?php

namespace App\Form;

use App\Entity\Erabakia;
use App\Entity\Liburua;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ErabakiaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adata', DateTimeType::class, [
                'label' => 'Data',
                'widget' => 'single_text',
                'html5' => false,
                'format' => "yyyy-MM-dd",
                'attr' => [
                    'class' => 'form-control input-inline datepicker'
                ]
            ])
            ->add('gaiak', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('temas', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('pdfFile', VichFileType::class, [
                'label' => 'Fitxategia',
                'allow_delete' => true,
                'download_label' => 'Ikusi fitxategia',
                'delete_label' => 'Markatu hemen fitxategia ezabatzeko eta gorde botoia klikatu',
                'download_uri' => true,
                'attr' => [
                    'placeholder' => 'Aukeratu fitxategia'
                ],
                'required' => false
            ])
            ->add('oharrak', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('observaciones', CKEditorType::class, [
                'config_name' => 'pasaia_config',
                'config'      => array('uiColor' => '#ffffff'),
            ])
            ->add('akta')
            ->add('liburua', EntityType::class, [
                'class' => Liburua::class,
                'placeholder' => 'Aukeratu bat',
                'required' => true
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
