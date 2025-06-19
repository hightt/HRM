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
use App\Model\LeaveRequest\LeaveRequestType;

class LeaveRequestDecideFormType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {}
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices' => LeaveRequestStatus::cases(),
                'choice_label' => fn(LeaveRequestStatus $choice) => $choice->label($this->translator),
                'choice_value' => fn(?LeaveRequestStatus $choice) => $choice?->value,
            ])
            ->add('managerComment')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LeaveRequest::class,
        ]);
    }
}
