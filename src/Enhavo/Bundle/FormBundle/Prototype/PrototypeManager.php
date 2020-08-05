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

    /** @var bool */
    private $csrfProtection;

    /**
     * PrototypeManager constructor.
     * @param TokenGeneratorInterface $tokenGenerator
     * @param bool $csrfProtection
     */
    public function __construct(TokenGeneratorInterface $tokenGenerator, $csrfProtection = true)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->csrfProtection = $csrfProtection;
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

    public function buildPrototype(FormBuilderInterface $builder, $storageName, $type, $options, $parameters)
    {
        if(!$this->hasStorage($storageName)) {
            throw new \InvalidArgumentException();
        }

        if($this->csrfProtection) {
            $options = array_merge($options, [
                'csrf_protection' => false
            ]);
        }

        $name = sprintf('__%s__', $this->tokenGenerator->generateToken(8));
        $form = $builder->create($name, $type, $options)->getForm();
        $this->prototypes[] = new Prototype($storageName, $name, $form, $parameters);
    }

    /**
     * @param $storageName
     * @return Prototype[]
     */
    public function getPrototypes($storageName): array
    {
        $prototypes = [];
        foreach($this->prototypes as $prototype) {
            if($prototype->getStorageName() === $storageName) {
                $prototypes[] = $prototype;
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
        $view->vars['attr']['data-prototype-full-name'] = $view->vars['full_name'];
    }

    public function buildPrototypeView(FormView $view)
    {

    }
}
