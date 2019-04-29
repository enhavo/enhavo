<?php
/**
 * Created by PhpStorm.
 * User: jenny
 * Date: 12.12.16
 * Time: 13:14
 */

namespace Enhavo\Bundle\AppBundle\Twig;

use Enhavo\Bundle\AppBundle\DependencyInjection\EnhavoAppExtension;
use Enhavo\Bundle\AppBundle\Util\Assetic;

class Branding extends \Twig_Extension
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
        return array(
            new \Twig_SimpleFunction('is_branding', array($this, 'isBranding')),
            new \Twig_SimpleFunction('is_branding_version', array($this, 'isBrandingVersion')),
            new \Twig_SimpleFunction('is_branding_created_by', array($this, 'isBrandingCreatedBy')),
            new \Twig_SimpleFunction('branding_text', array($this, 'getBrandingText'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('branding_logo', array($this, 'getBrandingLogo')),
            new \Twig_SimpleFunction('branding_version', array($this, 'getBrandingVersion')),
            new \Twig_SimpleFunction('branding_background_image', array($this, 'getBackgroundImage')),
        );
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

    public function getName()
    {
        return 'branding';
    }
}
