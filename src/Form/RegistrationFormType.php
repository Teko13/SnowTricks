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
                'label'=> 'Email',
                "attr" => [
                    "placeholder" => "Votre adresse mail"
                ]
            ])
            ->add("username", TextType::class, [
                "label"=> "Nom d'utilisateur",
                'attr' => [
                    "placeholder" => "Choisissez un nom d'utitlisateur"
                ],
                'constraints' => [
                    new NotBlank(['message'=> "Saisissez un nom d'utilisatzeur"]),
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label'=> 'Mot de passe', 
                'mapped' => false,
                'attr' => [
                    "placeholder" => "Saisissez un mot de passe (min:4)"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez un mot de passe',
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => 'Votre mot de passe doit avoir minimum {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
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
