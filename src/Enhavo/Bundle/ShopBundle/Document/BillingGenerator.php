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

class BillingGenerator
{
    /**
     * @var KernelInterface $kernel
     */
    protected $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function generate(OrderInterface $order)
    {
        $pdf = new BaseDocument();
        $pdf->AddPage();
        $pdf->setAddress($order->getShippingAddress());
        return $pdf->Output(null, 'S');
    }
}