<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 19:51
 */

namespace Enhavo\Component\Type;


use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractContainerType
{
    /** @var TypeInterface */
    protected $type;

    /** @var TypeInterface[] */
    protected $parents;

    /** @var array */
    protected $options;

    /** @var string|null */
    protected $key;

    /**
     * AbstractContainerType constructor.
     * @param TypeInterface $type
     * @param TypeInterface[] $parents
     * @param array $options
     * @param string|null $key
     */
    public function __construct(TypeInterface $type, array $parents, array $options, string $key = null)
    {
        $this->type = $type;
        $this->parents = $parents;
        $this->key = $key;

        $resolver = new OptionsResolver();
        foreach($this->parents as $parent) {
            $parent->configureOptions($resolver);
        }
        $this->type->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
    }
}
