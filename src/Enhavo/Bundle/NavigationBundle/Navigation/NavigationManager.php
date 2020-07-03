<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:10
 */

namespace Enhavo\Bundle\NavigationBundle\Navigation;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Voter\VoterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationManager
{
    /** @var VoterInterface[] */
    private $voters = [];

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

        $optionsResolver->setAllowedTypes('exclude', 'array');

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
        $usedVoters = $this->voters;

        if(is_array($voters)) {
            $usedVoters = [];
            foreach($voters as $includeVoter) {
                foreach ($this->voters as $voter) {
                    if (is_string($includeVoter) && get_class($voter) === $includeVoter) {
                        $usedVoters[] = $voter;
                    } elseif(is_object($includeVoter) && $voter === $includeVoter) {
                        $usedVoters[] = $voter;
                    }
                }
            }
        }

        if(count($exclude) > 0) {
            foreach($exclude as $excludeVoter) {
                foreach ($usedVoters as $index => $voter) {
                    if (is_string($excludeVoter) && get_class($voter) === $excludeVoter) {
                        unset($usedVoters[$index]);
                    } elseif(is_object($excludeVoter) && $voter === $excludeVoter) {
                        unset($usedVoters[$index]);
                    }
                }
            }
        }

        return $usedVoters;
    }

    public function addVoter(VoterInterface $voter)
    {
        $this->voters[] = $voter;
    }
}
