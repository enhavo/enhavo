<?php
/**
 * RouteTranslationType.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\RoutingBundle\Form\Type\RouteType;
use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteTranslationType extends AbstractTranslationType
{
    /**
     * @var RouteTranslator
     */
    private $routeTranslator;

    /**
     * RouteTranslationType constructor.
     * @param RouteTranslator $routeTranslator
     */
    public function __construct(RouteTranslator $routeTranslator)
    {
        $this->routeTranslator = $routeTranslator;
    }

    public function getFormType(array $options)
    {
        return $options['form_type'];
    }

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        $this->routeTranslator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        return $this->routeTranslator->getTranslation($data, $property, $locale);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'constraints' => [],
            'form_type' => RouteType::class
        ]);
    }

    public function getType()
    {
        return 'route';
    }
}
