<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\{SubmitType, TextareaType, TextType};
use App\Entity\Country;
use App\Entity\HidingPlace;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HidingPlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('city', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('postalCode', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('type', TextType::class, [
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HidingPlace::class,
        ]);
    }
}
