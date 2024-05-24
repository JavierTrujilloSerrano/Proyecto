<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Form\Type\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateUpdateUserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => new TranslatableMessage('user.name', [], 'forms'),
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => new TranslatableMessage('user.name', [], 'forms'),
                ],
            ])
            ->add('email', TextType::class, [
                'label' => new TranslatableMessage('user.email', [], 'forms'),
                'disabled' => true,
            ])
             ->add('password', TextType::class, [
            'label' => new TranslatableMessage('user.password', [], 'forms'),
            'disabled' => true,
        ]);
    }
}