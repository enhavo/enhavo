<?php
/**
 * AssetCollection.php
 *
 * @since 05/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AssetsBundle\Asset;

use Doctrine\Common\Collections\ArrayCollection;
use PlasmaConduit\DependencyGraph;
use PlasmaConduit\dependencygraph\DependencyGraphNode;
use PlasmaConduit\dependencygraph\DependencyGraphNodes;

class AssetCollection
{
    protected $collection;

    protected $nodes;

    protected $graph;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    public function add($name, $file, $depends = array())
    {
        $this->collection->add(array(
            'name' => $name,
            'file' => $file,
            'depends' => $depends
        ));
        return $this;
    }

    public function getList()
    {
        $this->buildNodes();
        $this->buildGraph();


        return $this->collection->toArray();
    }

    public function remove($name)
    {

    }

    private function buildGraph()
    {
        $this->graph = new DependencyGraphNodes();
        foreach($this->nodes as $node) {
            $this->graph->addSibling($node);
        }

        foreach($this->collection as $resource) {
            foreach($resource['depends'] as $depends) {
                $this->graph->addDependency(
                    $this->getNodeByName($depends),
                    $this->getNodeByName($resource['name'])
                );
            }
        }
    }

    /**
     * @return array
     */
    private function buildNodes()
    {
        $this->nodes = [];

        foreach ($this->collection as $resource) {
            $this->nodes[] = new DependencyGraphNode($resource['name']);
        }
    }

    /**
     * @param $name
     * @return DependencyGraphNode
     */
    private function getNodeByName($name)
    {
        foreach($this->nodes as $node) {
            /** @var $node DependencyGraphNode */
            if($node->getName() == $name) {
                return $node;
            }
        }
    }
}