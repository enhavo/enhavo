<?php
/**
 * ConfigurationType.php
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
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use esperanto\ContentBundle\Service\ContentTypeService;

class ConfigurationType extends AbstractType
{
    /**
     * @var ContentTypeService
     */
    protected $typeService;

    public function __construct($typeService)
    {
        $this->typeService = $typeService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $configuration = new Configuration();
        $typeService = $this->typeService;

        $builder->add('type', 'hidden');

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($typeService) {
                $data = $event->getData();
                $form = $event->getForm();

                if($data instanceof Configuration) {
                    $formType = $typeService->getFormTypeResolver()->resolve($data->getType(), null, $data);
                    $form->add('form', $formType);
                }

                return;
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use (&$configuration, $typeService) {
                $data = $event->getData();

                if($data === null) {
                    return;
                }

                if(!array_key_exists('type', $data)) {
                    throw new \Exception('no type set for configuration');
                }

                $configuration->setType($data['type']);
                unset($data['type']);

                $itemType = $typeService->getResolver()->resolve($configuration->getType(), $data);
                $configuration->setData($itemType);

                //set data to null to keep form synchronized
                $event->setData(null);
                return;
            }
        );

        //after normalization write back to model data
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use (&$configuration, $typeService) {
                $event->setData($configuration);
                return;
            }
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'esperanto\ContentBundle\Entity\Configuration'
        ));
    }

    public function getName()
    {
        return 'esperanto_content_configuration';
    }
} 