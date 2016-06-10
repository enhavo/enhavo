<?php
/**
 * Created by PhpStorm.
 * User: fliebl
 * Date: 10.06.16
 * Time: 09:15
 */

namespace Enhavo\Bundle\MediaBundle\Table\Widget;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\AppBundle\Table\AbstractTableWidget;
use Enhavo\Bundle\MediaBundle\Entity\File;

class PictureWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $property = $this->getProperty($item, $options['property']);

        $pictureWidth = (isset($options['pictureWidth']) ? $options['pictureWidth'] : 60);
        $pictureHeight = (isset($options['pictureHeight']) ? $options['pictureHeight'] : 60);

        $id = null;
        if (is_a($property, 'Doctrine\Common\Collections\Collection')) {
            /** @var Collection $property */
            if ($property->isEmpty()) {
                $id = null;
            } else {
                if ($this->checkIsCorrectFileType($property->first())) {
                    $propertyArray = $property->toArray();
                    usort($propertyArray, function($a, $b) {
                        /** @var File $a */
                        /** @var File $b */
                        if ($a->getOrder() == $b->getOrder()) {
                            return 0;
                        } else if ($a->getOrder() > $b->getOrder()) {
                            return 1;
                        } else {
                            return -1;
                        }
                    });
                    $id = array_shift($propertyArray);
                    $id = $id->getId();
                } else {
                    $id = null;
                }
            }
        } else {
            $id = $this->checkIsCorrectFileType($property);
        }

        return $this->renderTemplate('EnhavoMediaBundle:TableWidget:picture.html.twig', array(
            'id' => $id,
            'pictureWidth' => $pictureWidth,
            'pictureHeight' => $pictureHeight
        ));
    }

    private function checkIsCorrectFileType($property)
    {
        if (is_a($property, 'Enhavo\Bundle\MediaBundle\Entity\File')) {
            /** @var $property File */
            return $property->getId();
        } else {
            if (!$property) {
                return null;
            } else {
                $typeName = (is_object($property) ? get_class($property) : gettype($property));
                throw new \Exception('Error rendering TableWidget type PictureWidget: Property must be of type "Enhavo\Bundle\MediaBundle\Entity\File" or a Collection thereof, is "' . $typeName . '"');
            }
        }
    }

    public function getType()
    {
        return 'picture';
    }
}
