<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-15
 * Time: 18:04
 */

namespace Enhavo\Bundle\FormBundle\Prototype;

use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrototypeManager
{
    /** @var TokenGeneratorInterface */
    private $tokenGenerator;

    /** @var Prototype[] */
    private $prototypes = [];

    /** @var array */
    private $storage = [];

    /** @var FormBuilderInterface */
    private $formBuilder;

    /**
     * PrototypeManager constructor.
     * @param TokenGeneratorInterface $tokenGenerator
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(TokenGeneratorInterface $tokenGenerator, FormFactoryInterface $formFactory)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->formBuilder = $formFactory->createBuilder();
    }

    public function initStorage($storageName)
    {
        if($this->hasStorage($storageName)) {
            throw new \InvalidArgumentException();
        }
        $this->storage[$storageName] = true;
    }

    public function hasStorage($storageName)
    {
        return isset($this->storage[$storageName]);
    }

    public function buildPrototype($storageName, $type, $options, $parameters)
    {
        if(!$this->hasStorage($storageName)) {
            throw new \InvalidArgumentException();
        }

        $options = array_merge($options, [
            'csrf_protection' => false
        ]);

        $name = sprintf('__%s__', $this->tokenGenerator->generateToken(8));
        $form = $this->formBuilder->create($name, $type, $options)->getForm();
        $this->prototypes[] = new Prototype($storageName, $name, $form, $parameters);
    }

    public function getPrototypes($storageName): array
    {
        $prototypes = [];
        foreach($this->prototypes as $prototype) {
            if($prototype->getStorageName() === $storageName) {
                $prototypes[] = $prototype->getForm();
            }
        }
        return $prototypes;
    }

    public function normalizePrototypeStorage($optionName, OptionsResolver $resolver)
    {
        $tokenGenerator = $this->tokenGenerator;
        $resolver->setNormalizer($optionName, function($options, $value) use ($tokenGenerator) {
            if($value === null) {
                $value = $tokenGenerator->generateToken(8);
            }
            return $value;
        });
    }

    public function getPrototypeViews()
    {
        $data = [];
        foreach($this->prototypes as $prototype) {
            $data[] = new PrototypeView($prototype);
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    private function buildPrototypeConfiguration(FormView $view, FormInterface $form, array $options)
    {
        if($options['nested_prototype']) {
            $path = [$form->getName()];
            $parent = $form->getParent();
            while($parent !== null) {
                $path[] = $parent->getName();
                $parent = $parent->getParent();
            }
            $path = array_reverse($path);


            $view->vars['prototype_configuration'] = json_encode([
                'prototypeName' => $options['nested_prototype_name'],
                'nestedPath' => $path
            ]);
        }
    }

    private function buildNestedPath(FormView $view, FormInterface $form, array $options)
    {
        if($options['nested_path']) {
            $path = [$form->getName()];
            $parent = $form->getParent();
            while($parent !== null) {
                if($parent->getConfig()->getOption('nested_prototype')) {
                    return;
                };
                $path[] = $parent->getName();
                $parent = $parent->getParent();
            }
            $path = array_reverse($path);

            $view->vars['attr']['data-nested-path'] = json_encode($path);
            $view->vars['nested_path'] = json_encode($path);
        }
    }
}
