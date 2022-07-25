<?php
/**
 * DocumentManager.php
 *
 * @since 01/12/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Manager;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;
use Enhavo\Bundle\ShopBundle\Document\GeneratorInterface;
use Enhavo\Bundle\ShopBundle\Exception\DocumentGeneratorException;
use Enhavo\Bundle\ShopBundle\Model\OrderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentManager
{
    /** @var GeneratorInterface[]  */
    private array $generators = [];

    public function generateDocument($name, OrderInterface $order, $options = []): FileInterface
    {
        if (!array_key_exists($name, $this->generators)) {
            throw new DocumentGeneratorException(sprintf('Can\'t find generator with name "%s"', $name));
        }

        $generator = $this->generators[$name];

        $resolver = new OptionsResolver();
        $generator->configureOptions($resolver);
        $options = $resolver->resolve($options);

        return $generator->generate($order, $options);
    }

    public function addGenerator($name, GeneratorInterface $generator)
    {
        $this->generators[$name] = $generator;
    }
}
