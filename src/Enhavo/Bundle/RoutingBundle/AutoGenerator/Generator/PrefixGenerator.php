<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;


use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrefixGenerator extends AbstractGenerator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var EntityRepositoryInterface
     */
    private $routeRepository;

    public function __construct($em, $routeRepository)
    {
        $this->em = $em;
        $this->routeRepository = $routeRepository;
    }

    public function generate($resource, $options = [])
    {
        $value = $this->getProperty($resource, $options['property']);
        if($value !== null) {
            /** @var RouteInterface $route */
            $route = $this->getProperty($resource, $options['route_property']);
            if(!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix($this->createUrl($value, $options));
        }
    }

    protected function createUrl(string $string, array $options)
    {
        $string = $options['unique'] ? $this->getUniqueSlug($string) : $this->getSlug($string);
        return $this->getUrlFormat($string);
    }

    protected function getUrlFormat(string $string)
    {
        return sprintf('/%s', $string);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'unique' => false
        ]);
        $resolver->setRequired([
            'property'
        ]);
    }

    public function getType()
    {
        return 'prefix';
    }

    private function getSlug($string)
    {
        return Slugifier::slugify($string);
    }

    /**
     * $isFirstTry prevents unintended increase. E.g. if user enters "12-12-12" and entry exists, it should become "12-12-12-1", not "12-12-13".
     */
    private function getUniqueSlug(string $string, $isFirstTry = true)
    {
        $string = $this->getSlug($string);

        $results = $this->routeRepository->findBy([
            'staticPrefix' => $this->getUrlFormat($string)
        ]);

        if(count($results)){
            return $this->getUniqueSlug($this->increase($string, $isFirstTry), false);
        } else {
            return $string;
        }
    }

    private function increase($string, $isFirstTry)
    {
        if(!$isFirstTry){
            $isMatch = preg_match('/^(.*)-([0-9]+)$/', $string, $matches);
            if($isMatch && isset($matches[1]) && isset($matches[2])){
                return sprintf('%s-%u', $matches[1], intval($matches[2]) + 1);
            }
        }
        return sprintf('%s-1', $string);
    }
}
