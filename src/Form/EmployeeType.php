<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\Employee;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\DepartmentRepository;
use App\Entity\Department;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('birthdayDate', null, [
                'widget' => 'single_text',
            ])
            ->add('pesel')
            ->add('position')
            ->add('phoneNumber')
            ->add('address')
            ->add('salary')
            ->add('status')
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Kobieta' => 'K',
                    'Mężczyzna' => 'M',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Tak' => '1',
                    'Nie' => '0',
                ],
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $departmentRepository) {
                    return $departmentRepository->createQueryBuilder('d')
                        ->distinct();
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}
