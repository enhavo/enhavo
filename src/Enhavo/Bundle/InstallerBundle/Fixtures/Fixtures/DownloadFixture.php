<?php

/**
 * DownloadFixture.php
 *
 * @since 04/08/16
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\InstallerBundle\Fixtures\Fixtures;

use Enhavo\Bundle\InstallerBundle\Fixtures\AbstractFixture;
use Enhavo\Bundle\DownloadBundle\Entity\Download;

class DownloadFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     */
    function create($args)
    {
        $download = new Download();
        $download->setTitle($args['title']);
        $download->setText($args['text']);
        $download->setFile($this->createImage($args['file']));
        $this->translate($download);
        return $download;
    }

    /**
     * @inheritdoc
     */
    function getName()
    {
        return 'Download';
    }

    /**
     * @inheritdoc
     */
    function getOrder()
    {
        return 30;
    }
}
