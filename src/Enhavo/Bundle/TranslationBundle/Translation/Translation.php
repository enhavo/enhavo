<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-08-25
 * Time: 00:17
 */

namespace Enhavo\Bundle\TranslationBundle\Translation;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Translation
{
    /**
     * @var TranslationTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    public function __construct(TranslationTypeInterface $type, $options)
    {
        $this->type = $type;
        $resolver = new OptionsResolver();
        $this->type->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
}
