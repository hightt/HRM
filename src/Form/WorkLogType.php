<?php
declare(strict_types = 1);

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
                'label' => 'work_log.form.date',
                'attr' => [
                    'class'    => 'form-control',
                    'readonly' => 'readonly',
                    'style'    => 'width: 130px;',
                ],
                'format' => 'dd.MM.yyyy',
                'html5' => false,
            ])
            ->add('hourStart', TimeType::class, [
                'label'  => 'work_log.form.hour_start',
                'widget'  => 'choice',
                'minutes' => [0, 15, 30, 45],
                'attr' => [
                    'class' => 'input-select-work-log',
                    'style' => 'width: 120px;',
                    'placeholder' => 'work_log.form.hour_placeholder',
                ],
                'required' => false,
            ])
            ->add('hourEnd', TimeType::class, [
                'label'  => 'work_log.form.hour_end',
                'widget'  => 'choice',
                'minutes' => [0, 15, 30, 45],
                'attr' => [
                    'class' => 'input-select-work-log',
                    'style' => 'width: 120px;',
                    'placeholder' => 'work_log.form.hour_placeholder',
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
                'label'    => 'work_log.form.is_day_off',
                'required' => false,
                'attr'     => ['class' => 'form-check-input']
            ])
            ->add('absenceSymbol', EntityType::class, [
                'required' => false,
                'label'    => 'work_log.form.absence_symbol',
                'class'    => AbsenceSymbol::class,
                'query_builder' => function (AbsenceSymbolRepository $absenceSymbolRepository) {
                    return $absenceSymbolRepository->createQueryBuilder('a')
                        ->orderBy('a.name', 'ASC');
                },
                'choice_label' => function (AbsenceSymbol $absenceSymbol) {
                    return sprintf('%s - %s', $absenceSymbol->getName(), $absenceSymbol->getDescription());
                },
                'placeholder' => 'work_log.form.absence_symbol_placeholder',
                'attr' => ['class' => 'form-select']
            ])
            ->add('notes', TextareaType::class, [
                'label' => 'work_log.form.notes',
                'required' => false,
                'attr' => [
                    'class'       => 'form-control',
                    'placeholder' => 'work_log.form.notes_placeholder',
                    'style'       => 'width: 200px; height: 40px;'
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
