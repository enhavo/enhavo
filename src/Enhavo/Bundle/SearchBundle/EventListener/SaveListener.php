<?php

namespace Enhavo\Bundle\SearchBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Parser;

class SaveListener
{
    /**
     * @var Container
     */
    protected $container;
    protected $path;
    protected $requestStack;

    public function __construct(Container $container, $path, RequestStack $requestStack)
    {
        $this->container = $container;
        $this->path = $path;
        $this->requestStack = $requestStack;
    }

    public function onSave($event)
    {
        //search.yml der Entity auslesen um die zu indexierenden Felder zu finden
        $searchYamlPath = str_replace('/app', '/src', $this->path);
        $entityPath = get_class($event->getSubject());
        $splittedEntityPath = explode("\\", $entityPath);
        $i = 0;
        while($splittedEntityPath[$i] != 'Entity') {
            $searchYamlPath = $searchYamlPath.'/'.$splittedEntityPath[$i];
            $i++;
        }
        $searchYamlPath = $searchYamlPath.'/Resources/config/search.yml';
        $entityName = $splittedEntityPath[$i+1];
        $yaml = new Parser();
        $currentSearchYaml = $yaml->parse(file_get_contents($searchYamlPath));
        //Properties auslesen und diejenigen Felder indexieren
        $properties = $currentSearchYaml[$entityPath]['properties'];
        foreach($properties as $key => $value) {
            $zuIndexieren = $key;

            foreach ($value[0] as $key => $value) {
                if($key == 'Plain') {
                    //$this->indexingPlain();
                } else if($key == 'Html'){

                } else if($key == 'Collection') {

                }

            }

        }
    }

    public function indexingPlain($text) {

    }

    public function indexingHtml($text) {

    }

    public function indexingCollection($entity) {

    }
}