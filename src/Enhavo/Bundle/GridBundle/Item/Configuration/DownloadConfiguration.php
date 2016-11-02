<?php
/**
 * DownloadConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\GridBundle\Item\Configuration;

use Enhavo\Bundle\DownloadBundle\Entity\DownloadItem;
use Enhavo\Bundle\DownloadBundle\Form\Type\DownloadItemType;
use Enhavo\Bundle\GridBundle\Factory\DownloadFactory;

class DownloadConfiguration extends BaseConfiguration
{
    public function configure($name, $options)
    {
        $options['model'] = $this->getOption('model', $options, DownloadItem::class);
        $options['parent'] = $this->getOption('parent', $options, DownloadItem::class);
        $options['form'] = $this->getOption('form', $options, DownloadItemType::class);
        $options['factory'] = $this->getOption('factory', $options, DownloadFactory::class);
        $options['repository'] = $this->getOption('repository', $options, 'EnhavoDownloadBundle:DownloadItem');
        $options['template'] = $this->getOption('template', $options, 'EnhavoDownloadBundle:Item:download.html.twig');
        $options['label'] = $this->getOption('label', $options, 'download.label.download');
        $options['translationDomain'] = $this->getOption('translationDomain', $options, 'EnhavoGridBundle');

        return parent::configure($name, $options);
    }

    public function getType()
    {
        return 'download';
    }
}