<?php

namespace verzeilberg\UploadImagesBundle\Form\Image;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use verzeilberg\UploadImagesBundle\Entity\Image;

class Crop extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('detailX', HiddenType::class, [
                'required' => true,
                'attr' => ['id' => 'detailx']
            ])
            ->add('detailY', HiddenType::class, [
                'required' => true,
                'attr' => ['id' => 'detaily']
            ])
            ->add('detailW', HiddenType::class, [
                'required' => true,
                'attr' => ['id' => 'detailw']
            ])
            ->add('detailH', HiddenType::class, [
                'required' => true,
                'attr' => ['id' => 'detailh']
            ])
            ->add('save', SubmitType::class, [
                    'label' => 'Save',
                    'attr' => ['class' => 'btn-custom']
                ]
            );
    }
}
