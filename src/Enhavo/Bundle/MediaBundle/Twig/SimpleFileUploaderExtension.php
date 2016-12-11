<?php
/**
 * SimpleFileUploadExtension.php
 *
 * @since 09/12/16
 * @author gseidel
 */

namespace ProjectBundle\Twig;

use Enhavo\Bundle\MediaBundle\Entity\File;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\FormView;

class SimpleFileUploaderExtension extends \Twig_Extension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('simple_file_uploader', array($this, 'render'), array('is_safe' => array('html'))),
        );
    }

    public function render(FormView $formView, $inputTemplate, $itemTemplate)
    {
        $content = [];
        $index = 0;
        if($formView->vars['multiple']) {
            $content = [];
            $files = $formView->vars['data'];
            foreach($files as $file) {
                $content[] = $this->renderFileData($formView, $file, $itemTemplate, $index);
                $index++;
            }
        } else {
            if($formView->vars['data']) {
                $content[] = $this->renderFileData($formView, $formView->vars['data'], $itemTemplate);
            }
        }

        $content = implode('', $content);
        $prototype = $this->renderPrototype($formView, $itemTemplate);

        return $this->renderView($inputTemplate, array_merge($formView->vars, [
            'prototype' => $prototype,
            'content' => $content,
            'index' => $index
        ]));
    }

    public function renderView($template, $parameters = [])
    {
        return $this->container->get('templating')->render($template, $parameters);
    }

    public function renderFileData(FormView $formView, File $data, $template, $index = null)
    {
        $fullName = $formView->vars['full_name'];
        if($formView->vars['multiple']) {
            $prefix = sprintf('%s[%s]', $fullName, $index);
        } else {
            $prefix = sprintf('%s', $fullName);
        }

        return $this->renderView($template, array_merge($formView->vars, [
            'prefix' => $prefix,
            'index' => $index,
            'file' => $data,
            'id' => $data->getId(),
            'filename' => $data->getFilename(),
            'slug' => $data->getSlug()
        ]));
    }

    public function renderPrototype(FormView $formView, $template)
    {
        $index = '__index__';

        $fullName = $formView->vars['full_name'];
        if($formView->vars['multiple']) {
            $prefix = sprintf('%s[%s]', $fullName, $index);
        } else {
            $prefix = sprintf('%s', $fullName);
        }

        $file = new File();

        return $this->renderView($template, array_merge($formView->vars, [
            'prefix' => $prefix,
            'index' => $index,
            'file' => $file,
            'id' => '__id__',
            'filename' => '__filename__',
            'slug' => '__slug__'
        ]));
    }

    public function getName()
    {
        return 'simple_file_uploader';
    }
}