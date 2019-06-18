<?php
/**
 * BaseConfiguration.php
 *
 * @since 17/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\BlockBundle\Block\Configuration;

use Enhavo\Bundle\BlockBundle\Block\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseConfiguration extends AbstractConfiguration
{
    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
    }

    public function getType()
    {
        return 'base';
    }
}
