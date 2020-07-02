<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 19.07.18
 * Time: 18:10
 */

namespace Enhavo\Bundle\NavigationBundle\Navigation;

use Enhavo\Bundle\NavigationBundle\Model\NodeInterface;
use Enhavo\Bundle\NavigationBundle\Voter\Voter;
use Enhavo\Component\Type\Factory;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationManager
{
    /** @var Voter[] */
    private $voters;

    /** @var Factory */
    private $voterFactory;

    /** @var array */
    private $voterConfiguration;

    /**
     * NavigationManager constructor.
     * @param Factory $voterFactory
     * @param array $voterConfiguration
     */
    public function __construct(Factory $voterFactory, array $voterConfiguration)
    {
        $this->voterFactory = $voterFactory;
        $this->voterConfiguration = $voterConfiguration;
    }

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
            if($result === Voter::VOTE_IN) {
                $in = true;
            }
            if($result === Voter::VOTE_OUT) {
                return false;
            }
        }
        return $in;
    }

    /**
     * @param null|string[] $voters
     * @param array $exclude
     * @return Voter[]
     */
    private function getVoters($voters = null, $exclude = [])
    {
        $this->loadVoters();

        $usedVoters = $this->voters;

        if(is_array($voters)) {
            $usedVoters = [];
            foreach($this->voters as $name => $voter) {
                if(in_array($name, $voters)) {
                    $usedVoters[] = $voter;
                }
            }
        }

        if(count($exclude) > 0) {
            $includeVoters = [];
            foreach($usedVoters as $name => $voter) {
                if(!in_array($voter, $exclude)) {
                    $includeVoters[] = $voter;
                }
            }
            $usedVoters = $includeVoters;
        }

        return $usedVoters;
    }

    private function loadVoters()
    {
        if($this->voters === null) {
            foreach($this->voterConfiguration as $name => $options) {
                $this->voters[$name] = $this->voterFactory->create($options);
            }
        }
    }
}
