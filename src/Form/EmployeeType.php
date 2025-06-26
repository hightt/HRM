<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Department;
use App\Model\Employee\ContractType;
use App\Repository\DepartmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'employee.form.first_name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.first_name_placeholder',
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'employee.form.last_name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.last_name_placeholder',
                ],
            ])
            ->add('birthdayDate', DateType::class, [
                'label' => 'employee.form.birthday_date',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('pesel', TextType::class, [
                'label' => 'employee.form.pesel',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.pesel_placeholder',
                ],
            ])
            ->add('position', TextType::class, [
                'label' => 'employee.form.position',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.position_placeholder',
                ],
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'employee.form.phone_number',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.phone_number_placeholder',
                ],
            ])
            ->add('address', TextType::class, [
                'label' => 'employee.form.address',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.address_placeholder',
                ],
            ])
            ->add('contractType', ChoiceType::class, [
                'label' => 'employee.form.contract_type',
                'choices' => ContractType::cases(),
                'choice_label' => fn($type) => $type->label(), 
                'placeholder' => 'employee.form.contract_type',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('amountOfWorkingTime', ChoiceType::class, [
                'label' => 'employee.form.amount_of_working_time',
                'choices' => [
                    '8/8' => '8/8',
                    '7/8' => '7/8',
                    '6/8' => '6/8',
                    '5/8' => '5/8',
                    '4/8' => '4/8',
                ],
                'placeholder' => 'employee.form.amount_of_working_time_placeholder',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('salary', NumberType::class, [
                'label' => 'employee.form.salary',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'employee.form.salary_placeholder',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'employee.form.gender',
                'choices' => [
                    'employee.form.gender_female' => 'W',
                    'employee.form.gender_male' => 'M',
                ],
                'placeholder' => 'employee.form.gender',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'employee.form.status_placeholder',
                'choices' => [
                    'employee.form.active_yes' => '1',
                    'employee.form.active_no' => '0',
                ],
                'placeholder' => 'employee.form.status_placeholder',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('department', EntityType::class, [
                'label' => 'employee.form.department',
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $departmentRepository) {
                    return $departmentRepository->createQueryBuilder('d')
                        ->distinct()
                        ->orderBy('d.name', 'ASC');
                },
                'choice_label' => 'name',
                'placeholder' => 'employee.form.department',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('employmentDate', DateType::class, [
                'label' => 'employee.form.employment_date',
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
            'translation_domain' => 'messages',
        ]);
    }
}
