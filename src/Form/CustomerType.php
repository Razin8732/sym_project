<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Product;


class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'data' => $options['firstName'] ?: '',
                'empty_data' => '',
                'attr' => ['class' => 'form-control'],

            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'empty_data' => '',
                'data' => $options['lastName'] ?: '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'empty_data' => '',
                'data' => $options['email'] ?: '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Phone Number',
                'empty_data' => '',
                'data' => $options['phoneNumber'] ?: '',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dob', TextType::class, [
                'label' => 'Date Of Birth',
                'empty_data' => '',
                'data' => $options['dob'] ?: '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Gender',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female'
                ],
                'expanded' => true,
                'empty_data' => '',
                'data' => $options['gender'] ?: '',
                'attr' => ['class' => 'form-check']
            ])
            ->add('hobbies', ChoiceType::class, [
                'label' => 'Hobbies',
                'choices' => [
                    'Cricket' => 'Cricket',
                    'FootBall' => 'FootBall',
                    'VolleyBall' => 'VolleyBall',
                    'Hocky' => 'Hocky',
                ],
                'expanded' => false,
                'multiple' => true,
                // 'empty_data' => '',
                'data' => $options['hobbies'] ?: [],
                'attr' => ['class' => 'form-control']
            ])
            ->add('address', TextareaType::class, [
                'label' => 'Address',
                'empty_data' => '',
                'data' => $options['address'] ?: '',
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', FileType::class, [
                'label' => 'Image Upload',
                'mapped' => true,
                'required' => $options['img_required'],
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
                'attr' => ['class' => 'form-control', 'data-filename' => $options['image']]

            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                // "empty_data"  => new ArrayCollection,
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => true,
                'label' => 'Select Product',
                // 'mapped' => false,
                'attr' => ['class' => 'form-select'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'attr' => ['class' => 'btn btn-primary mx-2'],
            ])
            ->add('cancel', ButtonType::class, [
                'label' => 'Cancel',
                'attr' => ['class' => 'btn btn-secondary', 'onClick' => 'window.location.href="/customers"'],
            ]);
        // ->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'firstName' => null,
            'lastName' => null,
            'email' => null,
            'phoneNumber' => null,
            'dob' => null,
            'gender' => null,
            'hobbies' => null,
            'address' => null,
            'image' => null,
            'img_required' => true,
        ]);
    }
}
