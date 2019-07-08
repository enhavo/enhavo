<?php
/**
 * Created by PhpStorm.
 * User: fliebl
 * Date: 10.06.16
 * Time: 09:15
 */

namespace Enhavo\Bundle\MediaBundle\Column;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\AppBundle\Column\AbstractColumnType;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaColumn extends AbstractColumnType
{
    public function createResourceViewData(array $options, $item)
    {
        $property = $options['property'];
        $format = $options['format'];
        $height = $options['height'];

        $file = $this->getProperty($item, $property);

        if($file == null) {
            return [
                'height' => $height,
                'url' => null
            ];
        }

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

        $url = $this->container->get('enhavo_media.media.public_url_generator')->generateFormat($file, $format);

        return [
            'height' => $height,
            'url' => $url
        ];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'height' => 60,
            'format' => 'enhavoTableThumb',
            'component' => 'column-media',
        ]);
        $resolver->setRequired(['property']);
        $resolver->remove(['sortable']);
    }

    public function getType()
    {
        return 'media';
    }
}
