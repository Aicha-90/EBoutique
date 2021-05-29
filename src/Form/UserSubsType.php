<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserSubsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('password',PasswordType::class, [
            'mapped' => false,
            'constraints' => [
                new NotBlank([
                    'message' => 'Enter your password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password must have minimum {{ limit }} caracters',
                    'max' => 4096,
                ]),
            ],
        ])
            ->add('nom')
            ->add('prenom')
            ->add('email', EmailType::class)
            ->add('sexe', Type\ChoiceType::class,['choices' => ['Femme' => 'f', 'homme' => 'h'], "label" => false,
            'expanded' => true,
            'multiple' => false])

            ->add('ville')
            ->add('cp')
            ->add('adresse')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
