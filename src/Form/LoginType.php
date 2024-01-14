<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', TextType::class, [
                'label'=> "Nom d'utilisateur",
                "attr" => [
                    "placeholder"=> "Votre nom d'utilisateur",
                ],
                "constraints" => [
                    new NotBlank(["message" => "Veuillez saisir votre nom d'utilisateur"])
                ] 
                ])
            ->add("password", PasswordType::class, [
                "label"=> "Mot de passe",
                "attr" => [
                    "placeholder"=> "Votre mot de pass",
                ],
                "constraints" => [
                    new NotBlank(["message" => "Veuillez saisir votre mot de passe"])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
