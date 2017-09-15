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
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class PictureWidget extends AbstractTableWidget
{
    public function render($options, $item)
    {
        $property = $this->getRequiredOption('property', $options);
        $format = $this->getOption('format', $options, 'enhavoTableThumb');
        $height = $this->getOption('height', $options, '60');

        $file = $this->getProperty($item, $property);

        if ($file instanceof Collection && $file->first() instanceof FileInterface) {
            $files = $file->toArray();
            usort($files, function($a, $b) {
                /** @var FileInterface $a */
                /** @var FileInterface $b */
                if ($a->getOrder() == $b->getOrder()) {
                    return 0;
                } else if ($a->getOrder() > $b->getOrder()) {
                    return 1;
                } else {
                    return -1;
                }
            });
            $file = $files[0];
        }

        if($file !== null && !$file instanceof FileInterface) {
            throw new FileException(sprintf(
                'Error rendering TableWidget type PictureWidget: Property must be of type "Enhavo\Bundle\MediaBundle\Model\FileInterface" or a Collection thereof, is "%s"',
                get_class($file)
            ));
        }

        return $this->renderTemplate('EnhavoMediaBundle:TableWidget:picture.html.twig', array(
            'file' => $file,
            'format' => $format,
            'height' => $height
        ));
    }

    public function getType()
    {
        return 'picture';
    }
}
