<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Department;
use App\Repository\EmployeeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DepartmentType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label' => $this->translator->trans('department.form.name'),
                'attr' => ['class' => 'form-control', 'placeholder' => $this->translator->trans('department.form.name_placeholder')]
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
                'label' => $this->translator->trans('department.form.manager'),
                'placeholder' => $this->translator->trans('department.form.manager_placeholder'),
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('location', TextType::class, [
                'label' => $this->translator->trans('department.form.location'),
                'attr' => ['class' => 'form-control', 'placeholder' => $this->translator->trans('department.form.location_placeholder')]
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
