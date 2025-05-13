<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Type;

use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractContainerType
{
    protected TypeInterface $type;

    /** @var TypeInterface[] */
    protected array $parents;
    protected array $options;
    protected ?string $key;
    /** @var TypeExtensionInterface[] */
    protected array $extensions;

    /**
     * AbstractContainerType constructor.
     *
     * @param TypeInterface[] $parents
     */
    public function __construct(TypeInterface $type, array $parents, array $options, ?string $key = null, array $extensions = [])
    {
        $this->type = $type;
        $this->parents = $parents;
        $this->key = $key;
        $this->extensions = $extensions;

        $resolver = new OptionsResolver();
        foreach ($this->parents as $parent) {
            $parent->configureOptions($resolver);
            foreach ($this->extensions as $extension) {
                if ($this->isExtendable($parent, $extension)) {
                    $extension->configureOptions($resolver);
                }
            }
        }
        $this->type->configureOptions($resolver);
        foreach ($this->extensions as $extension) {
            if ($this->isExtendable($this->type, $extension)) {
                $extension->configureOptions($resolver);
            }
        }

        try {
            $this->options = $resolver->resolve($options);
        } catch (MissingOptionsException $exception) {
            throw new MissingOptionsException(sprintf('%s (%s): %s', get_class($type), $this->key, $exception->getMessage()), $exception->getCode(), $exception);
        } catch (InvalidOptionsException $exception) {
            throw new InvalidOptionsException(sprintf('%s (%s): %s', get_class($type), $this->key, $exception->getMessage()), $exception->getCode(), $exception);
        } catch (UndefinedOptionsException $exception) {
            throw new UndefinedOptionsException(sprintf('%s (%s): %s', get_class($type), $this->key, $exception->getMessage()), $exception->getCode(), $exception);
        }
    }

    protected function isExtendable(TypeInterface $type, TypeExtensionInterface $extension): bool
    {
        foreach ($extension::getExtendedTypes() as $extendedType) {
            if ($type::class === $extendedType || ($type::getName() && $type::getName() === $extendedType)) {
                return true;
            }
        }

        return false;
    }
}
