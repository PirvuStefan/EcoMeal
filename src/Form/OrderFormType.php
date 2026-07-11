<?php

namespace App\Form;

use App\Entity\Consumer;
use App\Entity\Order;
use App\Entity\Package;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('created_at', DateTimeType::class, [
                'label' => 'Created at',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('package', EntityType::class, [
                'class' => Package::class,
                'choice_label' => 'description',
            ])
            ->add('consumer', EntityType::class, [
                'class' => Consumer::class,
                'choice_label' => function (Consumer $consumer): string {
                    return sprintf(
                        '%s %s',
                        (string) $consumer->getFirstName(),
                        (string) $consumer->getLastName()
                    );
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
