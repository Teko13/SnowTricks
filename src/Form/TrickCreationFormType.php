<?php

namespace App\Form;

use App\Entity\Groupe;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

class TrickCreationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                "label" => " ",
                "attr" => [
                    "placeholder"=> "Nom de la figure",
                ],
                'constraints' => [
                    new NotBlank(["message" => "Veuillez ajouter le nom de la figure"])
                ]
            ])
            ->add("description", TextareaType::class, [
                "required" => true,
                "label" => "Descripteion",
                "attr" => [
                    "rows" => '15',
                    "placeholder" => "Description de la figure"
                ],
                "constraints" => [
                    new NotBlank(["message"=> "Vous devez fournir la description de la figure"])
                ]
            ])
            ->add("groupe_id", EntityType::class, [
                "class" => Groupe::class,
                "choice_label" => "name",
                "placeholder" => "Choisissez un groupe",
                "constraints" => [
                    new NotBlank(["message"=> "Choisissez un groupe"])
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
