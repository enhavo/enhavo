<?php

namespace Enhavo\Bundle\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * @author gseidel
 */
class SortingManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handleSort(Request $request, RequestConfiguration $configuration, EntityRepository $repository)
    {
        $parentProperty = $this->getParentProperty($configuration);
        $positionProperty = $this->getPositionProperty($configuration);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $content = json_decode($request->getContent(), true);
        $parent = $content['parent'] === null ? null: $repository->find($content['parent']);
        foreach($content['items'] as $position => $id) {
            $item = $repository->find($id);
            if($parentProperty) {
                $propertyAccessor->setValue($item, $parentProperty, $parent);
            }
            if($positionProperty) {
                $propertyAccessor->setValue($item, $positionProperty, $position);
            }
        }

        $this->em->flush();
    }

    private function getParentProperty(RequestConfiguration $configuration)
    {
        $options = $configuration->getViewerOptions();
        if(isset($options['parent_property'])) {
            return $options['parent_property'];
        }
        return null;
    }

    private function getPositionProperty(RequestConfiguration $configuration)
    {
        $options = $configuration->getViewerOptions();
        if(isset($options['position_property'])) {
            return $options['position_property'];
        }
        return null;
    }
}
