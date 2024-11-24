<?php
/**
 * Created by PhpStorm.
 * User: fliebl
 * Date: 10.06.16
 * Time: 09:15
 */

namespace Enhavo\Bundle\MediaBundle\Column;

use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\MediaBundle\Exception\FileException;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\MediaBundle\Routing\UrlGeneratorInterface;
use Enhavo\Bundle\ResourceBundle\Column\AbstractColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class MediaColumn extends AbstractColumnType
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function createResourceViewData(array $options, object $resource, Data $data): void
    {
        $propertyAccessor = new PropertyAccessor();

        $property = $options['property'];
        $format = $options['format'];
        $height = $options['height'];

        $file = $propertyAccessor->getValue($resource, $property);

        if ($file == null) {
            $data->set('height', $height);
            $data->set('url', null);
            return;
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

        $url = $this->urlGenerator->generateFormat($file, $format);


        $data->set('height', $height);
        $data->set('url', $url);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'height' => 60,
            'format' => 'enhavoTableThumb',
            'component' => 'column-media',
        ]);
        $resolver->setRequired(['property']);
        $resolver->remove(['sortable']);
    }

    public static function getName(): ?string
    {
        return 'media';
    }
}
