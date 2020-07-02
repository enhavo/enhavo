<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-29
 * Time: 11:19
 */

namespace Enhavo\Bundle\NavigationBundle\Voter\Type;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\Voter\VoterTypeInterface;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;
use Enhavo\Component\Type\TypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoterType implements VoterTypeInterface
{
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

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'disabled' => false
        ]);
    }

    public function vote(NodeInterface $node)
    {
        return Voter::VOTE_ABSTAIN;
    }

    public function isDisabled($options)
    {
        return $options['disabled'];
    }
}
