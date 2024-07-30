<?php
/**
 * ListDataViewer.php
 *
 * @since 22/04/19
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Endpoint\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointType;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Grid\Grid;
use Enhavo\Bundle\ResourceBundle\Grid\GridFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResourceListEndpointType extends AbstractEndpointType
{
    protected ?OptionsResolver $resolver;

    public function __construct(
        private readonly GridFactory $gridFactory,
    ) {}

    public function handleRequest($options, Request $request, Data $data, Context $context): void
    {
        /** @var Grid $grid */
        $grid = $this->gridFactory->create($options['grid']);

        $items = $grid->getItems($request->query->all());

        $data->add($items->normalize());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('grid');
    }

    public static function getName(): ?string
    {
        return 'resource_list';
    }
}
