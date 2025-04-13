<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\DepartmentRepository;
use App\Entity\Department;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Wpisz imię']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Wpisz nazwisko']
            ])
            ->add('birthdayDate', DateType::class, [
                'label' => 'Data urodzenia',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ])
            ->add('pesel', TextType::class, [
                'label' => 'PESEL',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Wpisz numer PESEL']
            ])
            ->add('position', TextType::class, [
                'label' => 'Stanowisko',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Podaj stanowisko']
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numer telefonu',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Podaj numer telefonu']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adres',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Podaj adres']
            ])
            ->add('contractType', ChoiceType::class, [
                'label' => 'Rodzaj zatrudnienia',
                'choices' => [
                    'Umowa o pracę'  => 'Umowa o pracę',
                    'B2B'            => 'B2B',
                    'Umowa zlecenie' => 'Umowa zlecenie',
                    'Umowa o dzieło' => 'Umowa o dzieło',
                ],
                'placeholder' => 'Rodzaj zatrudnienia',
                'attr' => ['class' => 'form-control']
            ])
            ->add('amountOfWorkingTime', ChoiceType::class, [
                'label' => 'Wymiar czasu pracy',
                'choices' => [
                    '8/8' => '8/8',
                    '7/8' => '7/8',
                    '6/8' => '6/8',
                    '5/8' => '5/8',
                    '4/8' => '4/8',
                ],
                'placeholder' => 'Wymiar czasu pracy',
                'attr' => ['class' => 'form-control']
            ])
            ->add('salary', NumberType::class, [
                'label' => 'Wynagrodzenie',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Podaj pensję']
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Płeć',
                'choices' => [
                    'Kobieta' => 'K',
                    'Mężczyzna' => 'M',
                ],
                'placeholder' => 'Wybierz płeć',
                'attr' => ['class' => 'form-control']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Czy aktywny?',
                'choices' => [
                    'Tak' => '1',
                    'Nie' => '0',
                ],
                'placeholder' => 'Wybierz status',
                'attr' => ['class' => 'form-control']
            ])
            ->add('department', EntityType::class, [
                'label' => 'Dział',
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $departmentRepository) {
                    return $departmentRepository->createQueryBuilder('d')
                        ->distinct()
                        ->orderBy('d.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'Wybierz dział',
                'attr' => ['class' => 'form-control']
            ])
            ->add('employmentDate', DateType::class, [
                'label' => 'Data zatrudnienia',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
