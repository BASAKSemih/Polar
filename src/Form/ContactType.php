<?php

namespace App\Form;

use App\Entity\{Contact, Nationality};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{DateType, SubmitType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('lastName', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('birthDate', DateType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('nationality', EntityType::class, [
                'label' => false,
                'class' => Nationality::class,
                'multiple' => true,
                'choice_label' => function (Nationality $nationality) {
                    return $nationality->getName();
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
            'data_class' => Contact::class,
        ]);
    }
}
