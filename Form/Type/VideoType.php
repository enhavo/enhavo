<?php
/**
 * TextType.php
 *
 * @since 23/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\ContentBundle\Form\Type;

use esperanto\ContentBundle\Entity\Configuration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use esperanto\ContentBundle\Item\Type\Video;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class VideoType extends AbstractType
{
    protected $formName;
    protected $configuration;

    public function __construct($formName, $configuration = null)
    {
        $this->formName = $formName;
        $this->configuration = $configuration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'hidden', array(
            'data' => 'video'
        ));
        $builder->add('url', 'text',array(
            'label' => 'Testlabel'
        ));

        if($this->configuration instanceof Configuration) {
            $data = $this->configuration->getData();
        } else {
            $data = null;
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($data) {

                if(!empty($data)) {
                    $event->setData($data);
                }
                return;
            }
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($this->formName) {
            $view->vars['full_name'] = $this->formName.'[configuration]';
        } else {
            $view->vars['full_name'] = preg_replace('/\[form\]/', '', $view->vars['full_name']);
        }

        return;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Item\Type\Video'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_item_video';
    }
}