<?php

namespace verzeilberg\UploadImagesBundle\Form\Image;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use verzeilberg\UploadImagesBundle\Entity\Image;

class Crop extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('detailX', TextType::class, [
                'required' => true,
                'attr' => ['id' => 'detailx']
            ])
            ->add('detailY', TextType::class, [
                'required' => true,
                'attr' => ['id' => 'detaily']
            ])
            ->add('detailW', TextType::class, [
                'required' => true,
                'attr' => ['id' => 'detailw']
            ])
            ->add('detailH', TextType::class, [
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
