<?php
/**
 * GeneratorInterface.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface GeneratorInterface
{
    public function generate(OrderInterface $order, $options = []): FileInterface;

    public function configureOptions(OptionsResolver $optionsResolver);
}
