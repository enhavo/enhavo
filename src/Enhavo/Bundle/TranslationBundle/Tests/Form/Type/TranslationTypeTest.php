<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Form\Type;

use Enhavo\Bundle\TranslationBundle\EventListener\AccessControlInterface;
use Enhavo\Bundle\TranslationBundle\Form\Extension\TranslationExtension;
use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationTypeTest extends TypeTestCase
{
    /** @var TranslationManager|MockObject */
    private $translationManager;
    private $accessControl;

    protected function getExtensions()
    {
        return [
            new PreloadedExtension([new TranslationType($this->translationManager)], []),
        ];
    }

    protected function getTypeExtensions()
    {
        $this->accessControl->method('isAccess')->willReturn(true);

        return [
            new TranslationExtension($this->translationManager, $this->accessControl),
        ];
    }

    public function setUp(): void
    {
        $this->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $this->accessControl = $this->getMockBuilder(AccessControlInterface::class)->getMock();
        parent::setUp();
    }

    public function testSubmitWithData()
    {
        $this->translationManager->method('isEnabled')->willReturn(true);
        $this->translationManager->method('isTranslatable')->willReturnCallback(function ($dataClass) {
            return $dataClass instanceof MockTranslationModel || MockTranslationModel::class === $dataClass;
        });
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturnCallback(function ($data, $property) {
            if ($data instanceof MockTranslationModel || MockTranslationModel::class === $data) {
                return ['de' => ''];
            }
        });

        $this->translationManager->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value): void {
            $this->assertTrue($data instanceof MockTranslationModel);
            $this->assertEquals('Hallo', $value);
        });

        $model = new MockTranslationModel();
        $form = $this->factory->create(MockTranslationFormType::class, $model);

        $form->submit([
            'text' => [
                'en' => 'Hello',
                'de' => 'Hallo',
            ],
        ]);

        $this->assertEquals('Hello', $model->getText());
    }

    public function testSubmitWithoutData()
    {
        $this->translationManager->method('isEnabled')->willReturn(true);
        $this->translationManager->method('isTranslatable')->willReturnCallback(function ($dataClass) {
            return $dataClass instanceof MockTranslationModel || MockTranslationModel::class === $dataClass;
        });
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturnCallback(function ($data, $property) {
            if ($data instanceof MockTranslationModel || MockTranslationModel::class === $data) {
                return ['de' => ''];
            }

            return ['de' => ''];
        });

        $this->translationManager->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value): void {
            $this->assertTrue($data instanceof MockTranslationModel);
        });

        $form = $this->factory->create(MockTranslationFormType::class);

        $form->submit([
            'text' => [
                'en' => 'Hello',
                'de' => 'Hallo',
            ],
        ]);

        $this->assertEquals('Hello', $form->getData()->getText());
    }

    public function testSameReferenceForSubmitAndTranslationData()
    {
        $this->translationManager->method('isEnabled')->willReturn(true);
        $this->translationManager->method('isTranslatable')->willReturnCallback(function ($dataClass) {
            return $dataClass instanceof MockTranslationModel || MockTranslationModel::class === $dataClass;
        });
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturn([]);

        $translationData = null;
        $this->translationManager->method('setTranslation')->willReturnCallback(function ($data, $property, $locale, $value) use (&$translationData): void {
            $translationData = $data;
        });

        $form = $this->factory->create(MockTranslationFormType::class);

        $form->submit([
            'text' => [
                'en' => 'Hello',
                'de' => 'Hallo',
            ],
        ]);

        $formData = $form->getData();

        $this->assertTrue($translationData === $formData);
    }

    public function testView()
    {
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturnCallback(function ($data, $property) {
            return ['de' => ''];
        });

        $form = $this->factory->create(TranslationType::class, null, [
            'form_options' => [],
            'form_type' => TextType::class,
            'translation_data' => new MockTranslationModel(),
            'translation_property' => 'text',
        ]);

        $form->addError(new FormError('(de) Error'));
        $form->addError(new FormError('Normal Error'));

        $view = $form->createView();

        $this->assertEquals(['en', 'de'], $view->vars['translation_locales']);
        $this->assertCount(2, $view->vars['errors']);
        $this->assertEquals('(de) Error', $view->vars['errors'][0]->getMessage());
        $this->assertEquals('(en) Normal Error', $view->vars['errors'][1]->getMessage());
        $this->assertTrue(in_array('enhavo_translation_translation', $view->vars['block_prefixes']));
    }
}

class MockTranslationModel
{
    /** @var string|null */
    private $text;

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }
}

class MockTranslationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MockTranslationModel::class,
        ]);
    }
}
