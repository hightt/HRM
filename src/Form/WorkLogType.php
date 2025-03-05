<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\WorkLog;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('hour_start', null, [
                'widget' => 'single_text',
            ])
            ->add('hourEnd', null, [
                'widget' => 'single_text',
            ])
            ->add('hoursNumber')
            ->add('overtimeNumber')
            ->add('isDayOff')
            ->add('absenceSymbol')
            ->add('employee', EntityType::class, [
                'class' => Employee::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkLog::class,
        ]);
    }
}
