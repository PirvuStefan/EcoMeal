<?php

namespace App\Form;

use App\Dto\PackageSearchFilter;
use App\Entity\Business;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Package;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PackageFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'label' => "Name"
            ])
            ->add('minPrice', NumberType::class, [
                'required' => false,
                'label' => "Min Price"
            ])
            ->add('maxPrice', NumberType::class, [
                'required' => false,
                'label' => "Max Price"
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Filter']);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PackageSearchFilter::class,
            'method' => 'GET',
        ]);
    }
}
