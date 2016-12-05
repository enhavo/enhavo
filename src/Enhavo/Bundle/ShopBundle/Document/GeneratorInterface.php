<?php
/**
 * GeneratorInterface.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;

interface GeneratorInterface
{
    /**
     * @param OrderInterface $order
     * @param array $options
     *
     * @return string
     */
    public function generate(OrderInterface $order, $options = []);

    /**
     * @param OrderInterface $order
     * @param array $options
     * 
     * @return string
     */
    public function generateName(OrderInterface $order, $options = []);
}