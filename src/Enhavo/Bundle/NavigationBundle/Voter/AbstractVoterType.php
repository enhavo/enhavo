<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-29
 * Time: 11:18
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractVoterType implements VoterTypeInterface
{
    /** @var VoterTypeInterface */
    protected $parent;

    public static function getName(): ?string
    {
        return null;
    }

    public static function getParentType(): ?string
    {
        return null;
    }

    public function setParent(TypeInterface $parent)
    {
        $this->parent = $parent;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function vote(NodeInterface $node)
    {
        return $this->parent->vote($node);
    }

    public function isDisabled($options)
    {
        return $this->parent->isDisabled($options);
    }
}
