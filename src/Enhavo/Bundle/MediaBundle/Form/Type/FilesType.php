<?php
/**
 * FilesType.php
 *
 * @since 13/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\MediaBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class FilesType extends AbstractType
{
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $manager = $this->manager;
        $collection = new ArrayCollection();

        //convert view data into concrete file objects
        //save it to collection var cause the normalization
        //will overwrite the model data immediately
        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($manager, &$collection) {
                $data = $event->getData();
                if($data) {
                    foreach($data as $formFile) {
                        $file = $manager->getRepository('enhavoMediaBundle:File')->find($formFile['id']);
                        $file->setOrder($formFile['order']);
                        $collection->add($file);
                    }
                }

                //set data to null to keep form synchronized
                $event->setData(null);
            }
        );

        //after normalization write back to model data
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($collection) {
                $event->setData($collection);
            }
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if(array_key_exists('information', $options) && is_array($options['information'])) {
            $view->information = $options['information'];
        } else {
            $view->information = array();
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(

        ));

        $resolver->setOptional(array(
            'information'
        ));
    }

    public function getName()
    {
        return 'enhavo_files';
    }

    public function getParent()
    {
        return 'form';
    }
}