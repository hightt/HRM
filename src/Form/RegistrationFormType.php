<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'registration.form.email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'registration.form.email_placeholder'
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'registration.form.agree_terms',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'registration.form.agree_terms_error',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'registration.form.plain_password',
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                    'placeholder' => 'registration.form.plain_password_placeholder'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'registration.form.plain_password_not_blank',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'registration.form.plain_password_min_length',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
