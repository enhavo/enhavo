<?php

namespace Enhavo\Bundle\MediaLibraryBundle\Media;

use Enhavo\Bundle\AppBundle\Template\TemplateTrait;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\MediaBundle\Extension\ExtensionInterface;
use Symfony\Component\Form\FormBuilderInterface;

class MediaLibraryExtension extends AbstractType implements ExtensionInterface
{
    use TemplateTrait;

    public function renderExtension($options)
    {
        return $this->renderTemplate($this->getTemplate('admin/resource/file/extension.html.twig'));
    }

    public function renderButton($options)
    {
        return $this->renderTemplate($this->getTemplate('admin/resource/file/button.html.twig'), []);
    }

    public function getType()
    {
        return 'media_library';
    }

    public function buildForm(FormBuilderInterface $builder, $options)
    {

    }
}
