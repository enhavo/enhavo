<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:06
 */

namespace Enhavo\Bundle\NavigationBundle\Voter;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Component\Type\TypeInterface;

interface VoterTypeInterface extends TypeInterface
{
    public function vote(NodeInterface $node);

    public function isDisabled($options);
}
