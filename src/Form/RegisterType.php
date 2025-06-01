<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'constraints' => new Length(['min' => 3]),
                'attr' => [
                    'placeholder' => 'Choose a username',
                    'class' => 'form-control bg-darker'
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => new Email(),
                'attr' => [
                    'placeholder' => 'Your email address',
                    'class' => 'form-control bg-darker'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Both passwords must match',
                'options' => ['attr' => ['class' => 'form-control bg-darker']],
                'required' => true,
                'first_options'  => [
                    'label' => 'Password',
                    'attr' => ['placeholder' => 'Create a password']
                ],
                'second_options' => [
                    'label' => 'Confirm Password',
                    'attr' => ['placeholder' => 'Confirm your password']
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Your password must be at least 4 characters long',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You must agree to the terms and services.',
                    ]),
                ],
                'label' => 'I agree to the terms and services',
                'required' => true,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'REGISTER NOW',
                'attr' => [
                    'class' => 'gaming-button btn-block w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
