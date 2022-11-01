<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la recette',
                'attr'  => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Veuillez renseigner un nom de recette'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(
                        min: 2,
                        max: 50
                    )
                ]
            ])
            ->add('time', TextType::class, [
                'label' => 'Temps de la recette',
                'attr'  => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Veuillez renseigner le temps de recette'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(
                        min: 1,
                        max: 24
                    )
                ]
            ])
            ->add('nbrPersonnes', TextType::class, [
                'label' => 'Nombre de personnes',
                'attr'  => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Veuillez renseigner le nombre de personnes pour cette recette'
                ],
                'constraints' => [
                    new Assert\LessThan(50, message: 'le nombre de personnes doit etre inferieur à 50')
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'label' => 'Difficulté de la recette',
                'attr'  => [
                    'class' => 'form-range mb-3',
                    'min' => 1,
                    'max' => 6
                ],
                'constraints' => [
                    new Assert\Positive(),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de la recette',
                'attr'  => [
                    'class' => 'form-control mb-3',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(
                        min: 2,
                        max: 300
                    )
                ]
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de la recette',
                'attr'  => [
                    'class' => 'form-control mb-3',
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1000)
                ]
            ])
            ->add('isFavorite', CheckboxType::class, [
                'label' => 'Favorite ? ',
                'required' => false,
                'attr'  => [
                    'class' => 'form-check-input mb-3',
                ],
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'multiple' => true,
                'attr'  => [
                    'class' => 'form-control'
                ]
            ])
            ->add('submit', SubmitType::class,  [
                'attr'  => [
                    'class' => 'btn btn-primary mt-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
