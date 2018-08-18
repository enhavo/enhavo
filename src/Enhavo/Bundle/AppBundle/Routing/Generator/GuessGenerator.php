<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 20:00
 */

namespace Enhavo\Bundle\AppBundle\Routing\Generator;

use Enhavo\Bundle\AppBundle\Exception\PropertyNotExistsException;
use Enhavo\Bundle\AppBundle\Model\RouteInterface;
use Enhavo\Bundle\AppBundle\Routing\AbstractGenerator;
use Enhavo\Bundle\AppBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuessGenerator extends AbstractGenerator
{
    public function generate(RouteInterface $route, $options)
    {
        $value = $this->guessProperties($route->getContent(), $options);
        if($value !== null) {
            $route->setStaticPrefix(sprintf('/%s', Slugifier::slugify($value)));
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'properties' => [
                'title',
                'name',
                'headline',
                'slug'
            ]
        ]);
    }

    public function getType()
    {
        return 'guess';
    }

    private function guessProperties($model, $options)
    {
        $properties = $this->getContextProperties($model, $options);
        foreach($properties as $property) {
            $value = $this->getProperty($model, $property);
            if($value !== null) {
                return $value;
            }
        }
        return null;
    }

    private function getContextProperties($model, $options)
    {
        $possibleProperties = [];

        foreach($options['properties'] as $property) {
            try {
                $this->getProperty($model, $property);
                $possibleProperties[] = $property;
            } catch (PropertyNotExistsException $e) {
                continue;
            }
        }

        return $possibleProperties;
    }
}