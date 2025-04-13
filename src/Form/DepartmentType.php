<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Department;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nazwa działu',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Wpisz nazwę działu']
            ])
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $employeeRepository) {
                    return $employeeRepository->createQueryBuilder('e')
                        ->distinct()
                        ->orderBy('e.lastName', 'ASC');
                },
                'choice_label' => function (Employee $employee) {
                    return sprintf('%d: %s %s', $employee->getId(), $employee->getLastName(), $employee->getFirstName());
                },
                'label' => 'Menedżer',
                'placeholder' => 'Wybierz menedżera',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => 'Lokalizacja',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Podaj lokalizację']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
