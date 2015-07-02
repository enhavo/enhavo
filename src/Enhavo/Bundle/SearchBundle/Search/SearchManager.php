<?php
/**
 * SearchManager.php
 *
 * @since 04/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace enhavo\SearchBundle\Search;


use Doctrine\Common\Persistence\ObjectManager;
use enhavo\SearchBundle\Entity\Index;

class SearchManager
{
    /**
     * @var ObjectManager
     */
    protected $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(SearchIndexInterface $index)
    {
        $searchIndex = $this->getIndex($index);

        if(empty($searchIndex)) {
            $searchIndex = $this->createIndex();
            $this->manager->persist($searchIndex);
            $searchIndex->setRoute($index->getIndexRoute());
            $searchIndex->setRouteParameter($index->getIndexRouteParameter());
        }

        $searchIndex->setContent($index->getIndexContent());
        $searchIndex->setTeaser($index->getIndexTeaser());
        $searchIndex->setTitle($index->getIndexTitle());

        $this->manager->flush();
    }

    public function search($query)
    {
        return $this->manager->getRepository('enhavoSearchBundle:Index')->search($query, 20);
    }

    protected function getIndex(SearchIndexInterface $index)
    {
        $repository = $this->manager->getRepository('enhavoSearchBundle:Index');
        $indexList = $repository->findBy(array('route' => $index->getIndexRoute()));
        if(count($indexList)) {
            foreach($indexList as $item) {
                $routeParameter = $item->getRouteParameter();
                if($this->equalsRouteParameter($routeParameter, $index->getIndexRouteParameter())) {
                    return $item;
                }
            }
        }
        return null;
    }

    protected function equalsRouteParameter($one, $two)
    {
        return !count(array_diff($one, $two));
    }

    /**
     * @return Index
     */
    protected function createIndex()
    {
        return new Index();
    }
} 