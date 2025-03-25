<?php

namespace App\Form;

use App\Entity\WorkLog;
use App\Entity\AbsenceSymbol;
use Symfony\Component\Form\AbstractType;
use App\Repository\AbsenceSymbolRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class WorkLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'attr' => [
                    'class'         => 'form-control',
                    'readonly'      => 'readonly',
                    'style'         => 'width: 130px;'
                ]
            ])
            ->add('hourStart', TimeType::class, [
                'widget' => 'choice',
                'minutes' => [0, 15, 30, 45],
                'attr' => [
                    'class' => 'input-select-work-log',
                    'style' => 'width: 120px;',
                    'placeholder' => 'hh:mm'
                ],
                'required' => false,
            ])
            ->add('hourEnd', TimeType::class, [
                'widget' => 'choice',
                'minutes' => [0, 15, 30, 45],
                'attr' => [
                    'class' => 'input-select-work-log',
                    'style' => 'width: 120px;',
                    'placeholder' => 'hh:mm'
                ],
                'required' => false,
            ])
            ->add('hoursNumber', HiddenType::class, [
                'required' => false,
            ])
            ->add('overtimeNumber', HiddenType::class, [
                'required' => false,
            ])
            ->add('isDayOff', CheckboxType::class, [
                'required' => false,
                'attr' => ['class' => 'form-check-input']
            ])
            ->add('absenceSymbol', EntityType::class, [
                'required' => false,
                'label' => 'Powód nieobecności',
                'class' => AbsenceSymbol::class,
                'query_builder' => function (AbsenceSymbolRepository $absenceSymbolRepository) {
                    return $absenceSymbolRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => function (AbsenceSymbol $absenceSymbol) {
                    return sprintf('%s - %s', $absenceSymbol->getName(), $absenceSymbol->getDescription());
                },
                'placeholder' => 'Wybierz powód nieobecności',
                'attr' => ['class' => 'form-select']
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Dodatkowe uwagi...',
                    'style' => 'width: 200px; height: 40px;'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkLog::class,
        ]);
    }
}
