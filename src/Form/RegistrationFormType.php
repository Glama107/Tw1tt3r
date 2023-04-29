<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                    'class' => 'appearance-none border border-gray-400 dark:border-gray-700 dark:caret-gray-950 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-800 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline-indigo'
                ]
            ])
            ->add('pseudo', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Pseudo',
                    'class' => 'appearance-none w-32 border border-gray-400 dark:border-gray-700 dark:caret-gray-950 rounded py-2 px-3 text-gray-700 dark:text-gray-800 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline-indigo'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'First name',
                    'class' => 'appearance-none border border-gray-400 dark:border-gray-700 dark:caret-gray-950 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-800 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline-indigo'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Last name',
                    'class' => 'appearance-none border border-gray-400 dark:border-gray-700 dark:caret-gray-950 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-800 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline-indigo'
                ]
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'class' => 'form-checkbox h-5 w-5 text-indigo-600'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'Password',
                    'class' => 'appearance-none border border-gray-400 dark:border-gray-700 dark:caret-gray-950 rounded w-full py-2 px-3 text-gray-700 dark:text-gray-800 leading-tight focus:outline-none focus:border-indigo-500 focus:shadow-outline-indigo'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
