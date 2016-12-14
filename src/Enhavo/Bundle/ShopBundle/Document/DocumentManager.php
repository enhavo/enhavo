<?php
/**
 * DocumentManager.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Document;

use Enhavo\Bundle\ShopBundle\Exception\DocumentGeneratorException;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DocumentManager
{
    use ContainerAwareTrait;

    public function generateBilling(OrderInterface $order, $options = [])
    {
        $generatorOptions = $this->getBillingOptions($options);
        $generator = $this->getBillingGenerator();
        return $generator->generate($order, $generatorOptions);
    }

    public function generateBillingName(OrderInterface $order, $options = [])
    {
        $generatorOptions = $this->getBillingOptions($options);
        $generator = $this->getBillingGenerator();
        return $generator->generateName($order, $generatorOptions);
    }

    /**
     * @return GeneratorInterface
     * @throws DocumentGeneratorException
     */
    private function getBillingGenerator()
    {
        $billingParameters = $this->container->getParameter('enhavo_shop.document.billing');
        $generatorName = $billingParameters['generator'];

        if(!$this->container->has($generatorName)) {
            throw new DocumentGeneratorException(sprintf('Service "%s" does not exist', $generatorName));
        }

        return $this->container->get($generatorName);
    }

    private function getBillingOptions($options)
    {
        $billingParameters = $this->container->getParameter('enhavo_shop.document.billing');
        $generatorOptions = [];
        if(isset($billingParameters['options'])) {
            $generatorOptions = $billingParameters['options'];
        }
        return array_merge($generatorOptions, $options);
    }

    public function generatePackingSlip(OrderInterface $order, $options = [])
    {
        $generatorOptions = $this->getPackingSlipOptions($options);
        $generator = $this->getPackingSlipGenerator();
        return $generator->generate($order, $generatorOptions);
    }

    public function generatePackingSlipName(OrderInterface $order, $options = [])
    {
        $generatorOptions = $this->getPackingSlipOptions($options);
        $generator = $this->getPackingSlipGenerator();
        return $generator->generateName($order, $generatorOptions);
    }

    /**
     * @return GeneratorInterface
     * @throws DocumentGeneratorException
     */
    private function getPackingSlipGenerator()
    {
        $packingSlipParameters = $this->container->getParameter('enhavo_shop.document.packing_slip');
        $generatorName = $packingSlipParameters['generator'];

        if(!$this->container->has($generatorName)) {
            throw new DocumentGeneratorException(sprintf('Service "%s" does not exist', $generatorName));
        }

        return $this->container->get($generatorName);
    }

    private function getPackingSlipOptions($options)
    {
        $packingSlipParameters = $this->container->getParameter('enhavo_shop.document.packing_slip');
        $generatorOptions = [];
        if(isset($packingSlipParameters['options'])) {
            $generatorOptions = $packingSlipParameters['options'];
        }
        return array_merge($generatorOptions, $options);
    }
}