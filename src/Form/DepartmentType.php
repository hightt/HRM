<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Department;
use App\Entity\Employee;
use App\Repository\DepartmentRepository;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('manager', EntityType::class, [
                'class' => Employee::class,
                'query_builder' => function (EmployeeRepository $employeeRepository) {
                    return $employeeRepository->createQueryBuilder('e')
                        ->distinct()
                        ->orderBy('e.lastName', 'ASC')
                        ;
                },
                'choice_label' => function (Employee $employee) {
                    return sprintf('%d: %s %s', $employee->getId(), $employee->getLastName(), $employee->getFirstName());
                },
            ])
            ->add('location')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Department::class,
        ]);
    }
}
