<?php

namespace App\Form;

use App\Entity\Pizza;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PizzaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de la pizza: ',
                'required' =>true
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description de la pizza: ',
                'required' =>true
            ])
            ->add('price', MoneyType::class,[
                'label' => 'Prix de la pizza: ',
                'required' =>true
            ])

            ->add('imageUrl', TextType::class,[
                'label' => 'Url de l\'image de la pizza: ',
                'required' =>true
            ])

            ->add('send', SubmitType::class,[
                'label' => 'Créer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pizza::class,
        ]);
    }
}
