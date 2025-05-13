<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BrandingExtension extends AbstractExtension
{
    /**
     * @var string[]
     */
    protected $brandingParameters;

    /**
     * @param string[] $brandingParameters
     */
    public function __construct($brandingParameters)
    {
        $this->brandingParameters = $brandingParameters;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('is_branding', [$this, 'isBranding']),
            new TwigFunction('is_branding_version', [$this, 'isBrandingVersion']),
            new TwigFunction('is_branding_created_by', [$this, 'isBrandingCreatedBy']),
            new TwigFunction('branding_text', [$this, 'getBrandingText'], ['is_safe' => ['html']]),
            new TwigFunction('branding_logo', [$this, 'getBrandingLogo']),
            new TwigFunction('branding_version', [$this, 'getBrandingVersion']),
            new TwigFunction('branding_background_image', [$this, 'getBackgroundImage']),
        ];
    }

    public function isBranding()
    {
        return $this->brandingParameters['enable'];
    }

    public function isBrandingVersion()
    {
        return $this->brandingParameters['enable_version'];
    }

    public function isBrandingCreatedBy()
    {
        return $this->brandingParameters['enable_created_by'];
    }

    public function getBrandingText()
    {
        return $this->brandingParameters['text'];
    }

    public function getBrandingLogo()
    {
        return $this->brandingParameters['logo'];
    }

    public function getBrandingVersion()
    {
        return $this->brandingParameters['version'];
    }

    public function getBackgroundImage()
    {
        return $this->brandingParameters['background_image'];
    }
}
