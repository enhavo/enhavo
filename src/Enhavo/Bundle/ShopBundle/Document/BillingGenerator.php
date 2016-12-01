<?php
/**
 * BillingGenerator.php
 *
 * @since 25/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document;

use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class BillingGenerator implements GeneratorInterface
{
    /**
     * @var KernelInterface $kernel
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function generate(OrderInterface $order, $options = [])
    {
        $pdf = new BaseDocument();
        $pdf->AddPage();
        $pdf->setAddress($order->getShippingAddress());
        return $pdf->Output(null, 'S');
    }

    public function generateName(OrderInterface $order, $options = [])
    {
        return sprintf('billing-%s.pdf', $order->getNumber());
    }
}