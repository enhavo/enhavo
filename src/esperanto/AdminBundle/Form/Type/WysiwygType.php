<?php
/**
 * WysiwygType.php
 *
 * @since 31/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Form\Type;

use esperanto\AdminBundle\Form\Config\WysiwygOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Rhumsaa\Uuid\Uuid;
use esperanto\AdminBundle\Form\Config\WysiwygConfig;

class WysiwygType extends AbstractType
{
    /**
     * @var WysiwygConfig
     */
    protected $config;

    public function __construct(WysiwygConfig $config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['id'] = Uuid::uuid4()->toString();

        $option = new WysiwygOption();
        $option->setFormats($options['formats']);
        $option->setToolbar1($options['toolbar1']);
        $option->setToolbar2($options['toolbar2']);
        $option->setHeight($options['height']);
        $option->setContentCss($options['content_css']);
        $view->vars['config'] = $this->config->getData($option);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'formats' => array(),
            'toolbar1' => null,
            'toolbar2' => null,
            'height' => null,
            'content_css' => null
        ));
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'wysiwyg';
    }
}