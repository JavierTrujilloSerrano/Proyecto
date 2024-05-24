<?php
declare(strict_types=1);

namespace Proyecto\Infrastructure\Form\Type\QuotedArticle;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateOrUpdateQuotedArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => new TranslatableMessage('quoted_article.name', [], 'forms'),
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => new TranslatableMessage('quoted_article.name', [], 'forms'),
                ],
            ])
            ->add('volumeInM3', NumberType::class, [
                'label' => new TranslatableMessage('quoted_article.volume', [], 'forms'),
                'constraints' => [
                    new NotBlank(),
                ],
                'attr' => [
                    'placeholder' => new TranslatableMessage('quoted_article.volume', [], 'forms'),
                    'min'=> 0,
                ],
                'scale' => 10,
                'rounding_mode' => \NumberFormatter::ROUND_HALFUP,
            ])
            ->add('weightInGrams', IntegerType::class, [
                'label' => new TranslatableMessage('quoted_article.weight', [], 'forms'),
                'constraints' => [
                    new NotBlank(),

                ],
                'attr' => [
                    'placeholder' => new TranslatableMessage('quoted_article.weight', [], 'forms'),
                    'min'=> 0,
                ],

            ])

            ->add('save', SubmitType::class, [
                'label' => new TranslatableMessage('quoted_article.save_btn', [], 'forms'),
            ]);
    }
}
