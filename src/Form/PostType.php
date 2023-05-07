<?php

declare(strict_types=1);

namespace App\Form;

use App\DTO\UpdatePost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Traversable;
use Webmozart\Assert\Assert;

use function iterator_to_array;

final class PostType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => UpdatePost::class,
                'empty_data' => null,
            ])
        ;
    }

    public function mapDataToForms(mixed $viewData, Traversable $forms): void
    {
        $forms = iterator_to_array($forms);

        /** @var array{title: FormInterface, content: FormInterface} $forms */
        /** @var UpdatePost|null $viewData */
        $forms['title']->setData($viewData?->title);
        $forms['content']->setData($viewData?->content);
    }

    public function mapFormsToData(Traversable $forms, mixed &$viewData): void
    {
        $forms = iterator_to_array($forms);

        /** @var array{title: FormInterface, content: FormInterface} $forms */
        $title = $forms['title']->getData();
        $content = $forms['content']->getData();

        Assert::nullOrString($title);
        Assert::nullOrString($content);

        $viewData = new UpdatePost($title, $content);
    }
}
