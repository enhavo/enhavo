<?php
/**
 * DownloadConfiguration.php
 *
 * @since 08/08/18
 * @author gseidel
 */

namespace Enhavo\Bundle\DownloadBundle\Item;

use Enhavo\Bundle\DownloadBundle\Entity\DownloadItem;
use Enhavo\Bundle\DownloadBundle\Factory\DownloadItemFactory;
use Enhavo\Bundle\DownloadBundle\Form\Type\DownloadItemType;
use Enhavo\Bundle\GridBundle\Item\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DownloadConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);

        $optionsResolver->setDefaults([
            'model' => DownloadItem::class,
            'parent' => DownloadItem::class,
            'form' => DownloadItemType::class,
            'factory' => DownloadItemFactory::class,
            'repository' => 'EnhavoGridBundle:Download',
            'template' => 'EnhavoGridBundle:Item:download.html.twig',
            'label' => 'download.label.download',
            'translationDomain' => 'EnhavoGridBundle',
        ]);
    }

    public function getType()
    {
        return 'download';
    }
}