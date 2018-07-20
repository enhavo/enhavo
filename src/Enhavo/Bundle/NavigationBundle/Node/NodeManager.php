<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:10
 */

namespace Enhavo\Bundle\NavigationBundle\Node;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Node\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeManager
{
    use ContainerAwareTrait;

    /**
     * @var VoterInterface[]
     */
    private $voters;

    /**
     * @param NodeInterface $node
     * @param array $options
     * @return bool
     */
    public function isActive(NodeInterface $node, $options = [])
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'voters' => null,
            'exclude' => []
        ]);
        $options = $optionsResolver->resolve($options);

        $voters = $this->getVoters($options['voters'], $options['exclude']);
        $in = false;
        foreach($voters as $voter) {
            $result = $voter->vote($node);
            if($result === VoterInterface::VOTE_IN) {
                $in = true;
            }
            if($result === VoterInterface::VOTE_OUT) {
                return false;
            }
        }
        return $in;
    }

    /**
     * @param null|string[] $voters
     * @param array $exclude
     * @return VoterInterface[]
     */
    private function getVoters($voters = null, $exclude = [])
    {
        if($this->voters === null) {
            $collector = $this->container->get('enhavo_navigation.voter_collector');
            $this->voters = $collector->getTypes();
        }

        $usedVoters = $this->voters;

        if(is_array($voters)) {
            $usedVoters = [];
            foreach($this->voters as $voter) {
                if(in_array($voter->getType(), $voters)) {
                    $usedVoters[] = $voter;
                }
            }
        }

        if(count($exclude) > 0) {
            $includeVoters = [];
            foreach($usedVoters as $voter) {
                if(!in_array($voter->getType(), $exclude)) {
                    $includeVoters[] = $voter;
                }
            }
            $usedVoters = $includeVoters;
        }

        return $usedVoters;
    }
}