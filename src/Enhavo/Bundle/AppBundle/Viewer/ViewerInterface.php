<?php
/**
 * ViewerInterface.php
 *
 * @since 20/06/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Viewer;

use Enhavo\Bundle\AppBundle\Controller\RequestConfigurationInterface;
use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Symfony\Component\Form\Form;

interface ViewerInterface extends TypeInterface
{
    public function createView();

    public function configureOptions(OptionAccessor $resolver);

    public function setMetadata(MetadataInterface $metadata);

    public function setContainer(ContainerInterface $container);

    public function setResource($resource);

    public function setForm(Form $form);

    public function setOptionAccessor(OptionAccessor $form);

    public function setConfiguration(RequestConfigurationInterface $configuration);
}