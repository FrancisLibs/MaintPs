<?php

namespace App\Form;

use App\Entity\Account;
use App\Data\SearchOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero', IntegerType::class, [
                'label' => false,
                'required'  => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])

            ->add('account', EntityType::class, [
                'label' => false,
                'required'  => false,
                'class' => Account::class,
                'expanded'  => true,
                'multiple' =>   true,
            ])

            ->add('Filtrer', SubmitType::class, [
                'label' => 'Filtrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchOrder::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}