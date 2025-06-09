<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Employee;
use App\Entity\LeaveRequest;
use Symfony\Component\Form\AbstractType;
use App\Model\LeaveRequest\LeaveRequestStatus;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Model\LeaveRequest\LeaveRequestType as LeaveRequestEnum;

class LeaveRequestType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('leaveType', ChoiceType::class, [
                'choices' => LeaveRequestEnum::choices($this->translator),
            ])
            ->add('dateFrom', null, [
                'widget' => 'single_text',
            ])
            ->add('dateTo', null, [
                'widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => LeaveRequestStatus::cases(),
                'choice_label' => fn(LeaveRequestStatus $choice) => $choice->value,
                'choice_value' => fn(?LeaveRequestStatus $choice) => $choice?->value,
            ])
            ->add('comment')
            ->add('managerComment')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
            ->add('reviewedBy', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeaveRequest::class,
        ]);
    }
}
