<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-15
 * Time: 17:02
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator;

use Enhavo\Bundle\TranslationBundle\Translator\Route\RouteTranslator;
use Enhavo\Bundle\TranslationBundle\Translator\Text\TextTranslator;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractLocaleGenerator implements LocaleGeneratorInterface
{
    use ContainerAwareTrait;

    /**
     * @var RouteTranslator
     */
    protected $routeTranslator;

    /**
     * @var TextTranslator
     */
    protected $textTranslator;

    /**
     * AbstractLocaleGenerator constructor.
     * @param RouteTranslator $routeTranslator
     * @param TextTranslator $textTranslator
     */
    public function __construct(RouteTranslator $routeTranslator, TextTranslator $textTranslator)
    {
        $this->routeTranslator = $routeTranslator;
        $this->textTranslator = $textTranslator;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
