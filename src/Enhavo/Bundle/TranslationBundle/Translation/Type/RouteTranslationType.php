<?php
/**
 * RouteTranslationType.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translation\Type;

use Enhavo\Bundle\TranslationBundle\Translation\AbstractTranslationType;
use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RouteTranslationType extends AbstractTranslationType
{
    /** @var RouteTranslator */
    private $routeTranslator;

    /**
     * RouteTranslationType constructor.
     * @param RouteTranslator $routeTranslator
     */
    public function __construct(RouteTranslator $routeTranslator)
    {
        $this->routeTranslator = $routeTranslator;
    }

    public function setTranslation(array $options, $data, $property, $locale, $value)
    {
        $this->routeTranslator->setTranslation($data, $property, $locale, $value);
    }

    public function getTranslation(array $options, $data, $property, $locale)
    {
        return $this->routeTranslator->getTranslation($data, $property, $locale);
    }

    public static function getName(): ?string
    {
        return 'route';
    }
}
