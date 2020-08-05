<?php
/**
 * LocaleGeneratorInterface.php
 *
 * @since 15/09/19
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\AutoGenerator;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface LocaleGeneratorInterface extends TypeInterface
{
    /**
     * @param object $resource
     * @param string $property
     * @param string $locale
     * @param array $options
     * @return void
     */
    public function generate($resource, $property, $locale, $options = []);

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver);
}
