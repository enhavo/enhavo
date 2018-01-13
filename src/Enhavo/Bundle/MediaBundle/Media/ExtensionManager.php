<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 22.09.17
 * Time: 17:51
 */

namespace Enhavo\Bundle\MediaBundle\Media;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;
use Enhavo\Bundle\MediaBundle\Extension\ExtensionInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class ExtensionManager
{
    /**
     * @var TypeCollector
     */
    private $extensionCollector;

    public function __construct(TypeCollector $extensionCollector)
    {
        $this->extensionCollector = $extensionCollector;
    }

    public function renderButtons($options)
    {
        $html = '';
        foreach($options as $key => $option) {
            /** @var ExtensionInterface $extension */
            $extension = $this->extensionCollector->getType($key);
            $html .= $extension->renderButton($option);
        }
        return $html;
    }

    public function renderExtensions($options)
    {
        $html = '';
        foreach($options as $key => $option) {
            /** @var ExtensionInterface $extension */
            $extension = $this->extensionCollector->getType($key);
            $html .= $extension->renderExtension($option);
        }
        return $html;
    }

    public function buildForm(FormBuilderInterface $builder, $options)
    {
        $extensionOptions = is_array($options['extensions']) ? $options['extensions'] : [];
        foreach($extensionOptions as $key => $option) {
            /** @var ExtensionInterface $extension */
            $extension = $this->extensionCollector->getType($key);
            $extension->buildForm($builder, $option);
        }
    }
}