<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

final class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $listener = function (PreSetDataEvent $event) {
            $image = $event->getData();
            $form = $event->getForm();
            if (! $image) {
                return;
            }

            if (null !== $image->getId()) {
                $form
                    ->remove('file');
            }
        };

        $builder
            ->add('filename', HiddenType::class)
            ->add('file',FileType::class, [
                'label' => 'Image (maxSize : 1024k)',
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA,$listener)
            ;      
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
