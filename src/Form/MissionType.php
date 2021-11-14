<?php

namespace App\Form;

use App\Entity\{Agent, Contact, HidingPlace, Mission, Target};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\{ChoiceType, DateType, SubmitType, TextareaType, TextType};
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('description', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('country', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('type', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'Surveillance' => 'Surveillance',
                    'Assassinat' => 'Assassinat',
                    'Infiltration' => 'Infiltration'
                ],
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('status', ChoiceType::class, [
                'required' => true,
                'label' => false,
                'choices' => [
                    'En préparation' => 'En préparation',
                    'En cours' => 'En cours',
                    'Terminé' => 'Terminé',
                    'Echec' => 'Echec',
                ],
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('speciality', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('dateStart', DateType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('dateEnd', DateType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('agent', EntityType::class, [
                'label' => false,
                'class' => Agent::class,
                'multiple' => true,
                'choice_label' => function (Agent $agent) {
                    return $agent->getFirstName() . $agent->getLastName();
                },
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('contact', EntityType::class, [
                'label' => false,
                'class' => Contact::class,
                'multiple' => true,
                'choice_label' => function (Contact $contact) {
                    return $contact->getFirstName() . $contact->getLastName();
                },
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('target', EntityType::class, [
                'label' => false,
                'class' => Target::class,
                'multiple' => true,
                'choice_label' => function (Target $target) {
                    return $target->getFirstName() . $target->getLastName();
                },
                'attr' => [
                    'class' => 'form-control'
                ]])
            ->add('hidingPlace', EntityType::class, [
                'label' => false,
                'class' => HidingPlace::class,
                'multiple' => true,
                'choice_label' => function (HidingPlace $hidingPlace) {
                    return $hidingPlace->getAddress();
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
            'data_class' => Mission::class,
        ]);
    }
}
