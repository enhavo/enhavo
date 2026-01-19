<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TranslationBundle\Tests\EventListener;

use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Form\EventListener\ReplaceTranslationTypeListener;
use Enhavo\Bundle\TranslationBundle\Form\Type\TranslationType;
use Enhavo\Bundle\TranslationBundle\Tests\Mocks\TranslatableMock;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplaceTranslationTypeListenerTest extends TypeTestCase
{
    private ReplaceTranslationTypeListener $listener;
    private ReplaceTranslationTypeListenerTestDependencies $dependencies;

    protected function setUp(): void
    {
        $this->dependencies = $this->createDependencies();
        $this->listener = $this->createInstance($this->dependencies);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        return [
            new PreloadedExtension([
                new TranslatableMockFormType($this->listener),
                new TranslationType($this->dependencies->translationManager),
            ], []),
        ];
    }

    protected function createDependencies(): ReplaceTranslationTypeListenerTestDependencies
    {
        $dependencies = new ReplaceTranslationTypeListenerTestDependencies();
        $dependencies->translationManager = $this->getMockBuilder(TranslationManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->formRenderer = $this->getMockBuilder(FormRendererInterface::class)->disableOriginalConstructor()->getMock();

        return $dependencies;
    }

    protected function createInstance($dependencies): ReplaceTranslationTypeListener
    {
        return new ReplaceTranslationTypeListener(
            $dependencies->translationManager,
            $dependencies->formRenderer,
        );
    }

    public function testSubscribedEvents(): void
    {
        $subscriber = $this->createInstance($this->createDependencies());

        $this->assertEquals([
            FormEvents::POST_SET_DATA => 'postSetData',
        ], $subscriber->getSubscribedEvents());
    }

    public function testPostSetDataTranslatable()
    {
        $this->dependencies->translationManager->expects($this->never())->method('isTranslatable');
        $this->dependencies->translationManager->expects($this->exactly(3))->method('isFormTranslatable')->willReturnCallback(function ($data, $property) {
            if (!$property) {
                return true;
            }

            return 'name' === $property;
        });
        $this->dependencies->translationManager->method('getLocales')->willReturn([
            'de', 'en',
        ]);
        $this->dependencies->translationManager->method('getTranslations')->willReturnCallback(function ($data, $property) {
            if ('name' === $property) {
                return [
                    'de' => new Translation(),
                    'en' => new Translation(),
                ];
            }

            return [];
        });

        $data = new TranslatableMock();
        $form = $this->factory->create(TranslatableMockFormType::class, $data);

        $fields = $form->all();
        $keys = array_keys($fields);

        $this->assertEquals([
            'name',
            'slug',
        ], $keys);
    }
}

class ReplaceTranslationTypeListenerTestDependencies
{
    public TranslationManager|MockObject $translationManager;
    public FormRendererInterface|MockObject $formRenderer;
}

class TranslatableMockFormType extends AbstractType
{
    public function __construct(
        private ReplaceTranslationTypeListener $listener,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('slug', TextType::class);
        $builder->addEventSubscriber($this->listener);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', TranslatableMock::class);
    }
}
