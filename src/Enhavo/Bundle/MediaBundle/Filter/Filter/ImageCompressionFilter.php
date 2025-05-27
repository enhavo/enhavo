<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MediaBundle\Filter\Filter;

use Enhavo\Bundle\MediaBundle\Filter\AbstractFilter;
use Enhavo\Bundle\MediaBundle\Model\FilterSetting;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\Optimizers\Gifsicle;
use Spatie\ImageOptimizer\Optimizers\Jpegoptim;
use Spatie\ImageOptimizer\Optimizers\Optipng;
use Spatie\ImageOptimizer\Optimizers\Pngquant;
use Spatie\ImageOptimizer\Optimizers\Svgo;

class ImageCompressionFilter extends AbstractFilter
{
    public function apply($file, FilterSetting $setting)
    {
        $content = $this->getContent($file);
        $optimizerChain = $this->createChainOptimizer($setting);
        $optimizerChain->optimize($content->getFilePath());
    }

    protected function createChainOptimizer(FilterSetting $setting)
    {
        $optimizer = new OptimizerChain();
        $optimizer->addOptimizer($this->createJpegoptim($setting));
        $optimizer->addOptimizer($this->createOptipng($setting));
        $optimizer->addOptimizer($this->createSvgo($setting));
        $optimizer->addOptimizer($this->createGifsicle($setting));
        $optimizer->addOptimizer($this->createPngquant($setting));

        return $optimizer;
    }

    private function createJpegoptim(FilterSetting $setting)
    {
        $optimizer = new Jpegoptim($setting->getSetting('arguments', [
            '-m85',
            '--strip-all',
            '--all-progressive',
        ]));
        $optimizer->binaryName = $this->container->getParameter('enhavo_media.filter.image_compression')['jpegoptim_path'];

        return $optimizer;
    }

    private function createOptipng(FilterSetting $setting)
    {
        $optimizer = new Optipng($setting->getSetting('arguments', [
            '-i0',
            '-o5',
            '-quiet',
        ]));
        $optimizer->binaryName = $this->container->getParameter('enhavo_media.filter.image_compression')['optipng_path'];

        return $optimizer;
    }

    private function createSvgo(FilterSetting $setting)
    {
        $optimizer = new Svgo($setting->getSetting('arguments', [
            '--disable=cleanupIDs',
        ]));
        $optimizer->binaryName = $this->container->getParameter('enhavo_media.filter.image_compression')['svgo_path'];

        return $optimizer;
    }

    private function createGifsicle(FilterSetting $setting)
    {
        $optimizer = new Gifsicle($setting->getSetting('arguments', [
            '-b',
            '-O3',
        ]));
        $optimizer->binaryName = $this->container->getParameter('enhavo_media.filter.image_compression')['gifsicle_path'];

        return $optimizer;
    }

    private function createPngquant(FilterSetting $setting)
    {
        $optimizer = new Pngquant($setting->getSetting('arguments', [
            '--force',
        ]));
        $optimizer->binaryName = $this->container->getParameter('enhavo_media.filter.image_compression')['pngquant_path'];

        return $optimizer;
    }

    /**
     * @inheritDoc
     */
    public function predictExtension(?string $originalExtension, FilterSetting $setting): ?string
    {
        return $originalExtension;
    }

    public function getType()
    {
        return 'image_compression';
    }
}
