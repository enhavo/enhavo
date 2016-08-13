<?php
/**
 * AbstractViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfiguration;
use Enhavo\Bundle\AppBundle\Type\AbstractType;
use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use FOS\RestBundle\View\View;

abstract class AbstractViewer extends AbstractType implements ViewerInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var mixed
     */
    protected $resource;

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var MetadataInterface
     */
    protected $metadata;

    /**
     * @var RequestConfiguration
     */
    protected $configuration;

    /**
     * @var OptionAccessor
     */
    protected $optionAccessor;

    /**
     * @param mixed $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param mixed $form
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param $resource
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param RequestConfigurationInterface $configuration
     */
    public function setConfiguration(RequestConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param MetadataInterface $metadata
     */
    public function setMetadata(MetadataInterface $metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @param OptionAccessor $options
     */
    public function setOptionAccessor(OptionAccessor $optionAccessor)
    {
        $this->optionAccessor = $optionAccessor;
    }

    /**
     * @return View
     */
    public function createView()
    {
        $view = View::create($this->resource, 200);
        $view->setTemplateData([
            'translationDomain' => $this->optionAccessor->get('translationDomain')
        ]);
        return $view;
    }

    public function configureOptions(OptionAccessor $optionsAccessor)
    {
        $optionsAccessor->setDefaults([
            'translationDomain' => null
        ]);
    }
}