<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-07-03
 * Time: 21:57
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\Form\Type;

use Enhavo\Bundle\TranslationBundle\Form\Extension\TranslationExtension;
use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationTypeTest extends TypeTestCase
{
    /** @var TranslationManager|\PHPUnit_Framework_MockObject_MockObject */
    private $translationManager;

    protected function getExtensions()
    {
        return [
            new PreloadedExtension([new TranslationType($this->translationManager)], [])
        ];
    }

    protected function getTypeExtensions()
    {
        return [
            new TranslationExtension($this->translationManager)
        ];
    }

    public function setUp()
    {
        $this->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        parent::setUp();
    }

    public function testSubmitWithData()
    {
        $this->translationManager->method('isTranslation')->willReturn(true);
        $this->translationManager->method('isEnabled')->willReturn(true);
        $this->translationManager->method('isTranslatable')->willReturnCallback(function ($dataClass) {
            return $dataClass instanceof MockTranslationModel || $dataClass === MockTranslationModel::class;
        });
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturnCallback(function($data, $property) {
            if ($data instanceof MockTranslationModel || $data === MockTranslationModel::class) {
                return ['de' => ''];
            }
        });

        $this->translationManager->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue($data instanceof MockTranslationModel);
            $this->assertEquals('Hallo', $value);
        });

        $model = new MockTranslationModel();
        $form = $this->factory->create(MockTranslationFormType::class, $model);

        $form->submit([
            'text' => [
                'en' => 'Hello',
                'de' => 'Hallo'
            ]
        ]);

        $this->assertEquals('Hello', $model->getText());
    }

    public function testSubmitWithoutData()
    {
        $this->translationManager->method('isTranslation')->willReturn(true);
        $this->translationManager->method('isEnabled')->willReturn(true);
        $this->translationManager->method('isTranslatable')->willReturnCallback(function ($dataClass) {
            return $dataClass instanceof MockTranslationModel || $dataClass === MockTranslationModel::class;
        });
        $this->translationManager->method('getLocales')->willReturn(['en', 'de']);
        $this->translationManager->method('getDefaultLocale')->willReturn('en');
        $this->translationManager->method('getTranslations')->willReturnCallback(function($data, $property) {
            if ($data instanceof MockTranslationModel || $data === MockTranslationModel::class) {
                return ['de' => ''];
            }
            return ['de' => ''];
        });

        $this->translationManager->method('setTranslation')->willReturnCallback(function($data, $property, $locale, $value) {
            $this->assertTrue($data instanceof MockTranslationModel);
        });

        $form = $this->factory->create(MockTranslationFormType::class);

        $form->submit([
            'text' => [
                'en' => 'Hello',
                'de' => 'Hallo'
            ]
        ]);

        $this->assertEquals('Hello', $form->getData()->getText());
    }
}

class MockTranslationModel
{
    /** @var string|null */
    private $text;

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string|null $text
     */
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
            'data_class' => MockTranslationModel::class
        ]);
    }
}
