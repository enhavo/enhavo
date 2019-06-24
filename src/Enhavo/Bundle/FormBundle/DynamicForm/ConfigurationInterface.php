<?php
/**
 * ConfigurationInterface.php
 *
 * @since 15/03/18
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\DynamicForm;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ConfigurationInterface extends TypeInterface
{
    public function getLabel(array $options);

    public function getTranslationDomain(array $options);

    public function configureOptions(OptionsResolver $options);
}
