<?php

namespace App\Form;

use App\Entity\Country;
use App\Entity\Nationality;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NationalityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('country', EntityType::class, [
                'label' => false,
                'class' => Country::class,
                'multiple' => false,
                'choice_label' => function (Country $country) {
                    return $country->getName();
                },
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('submit', SubmitType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'btn btn-lg btn-primary mt-2',
                ]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Nationality::class,
        ]);
    }
}
