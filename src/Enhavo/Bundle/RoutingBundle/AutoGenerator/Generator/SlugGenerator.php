<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class SlugGenerator extends AbstractGenerator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SlugGenerator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generate($resource, $options = [])
    {
        $value = $this->getProperty($resource, $options['property']);
        if($value !== null) {
            if(!$options['overwrite'] && $this->getProperty($resource, $options['slug_property'])) {
                return;
            }
            $accessor = PropertyAccess::createPropertyAccessor();
            $accessor->setValue($resource, $options['slug_property'], $this->createSlug($value, $resource, $options));
        }
    }

    protected function createSlug($value, $resource, $options) {
        return $options['unique'] ? $this->createUniqueSlug($value, $resource, $options) : Slugifier::slugify($value);
    }

    protected function createUniqueSlug($value, $resource, $options) {
        $baseSlug = Slugifier::slugify($value);

        if (!$this->slugExists($baseSlug, $resource, $options)) {
            return $baseSlug;
        }

        $postfixCount = 1;
        while ($this->slugExists($baseSlug . '-' . $postfixCount, $resource, $options)) {
            $postfixCount++;
        }
        return $baseSlug . '-' . $postfixCount;
    }

    protected function slugExists($slug, $resource, $options)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('COUNT(r.id) AS nr')
            ->from(get_class($resource), 'r')
            ->andWhere(sprintf('r.%s = :slug', $options['slug_property']))
            ->setParameter('slug', $slug);

        if ($resource->getId() !== null) {
            $queryBuilder->andWhere('r.id != :id')
                ->setParameter('id', $resource->getId());
        }

        return $queryBuilder->getQuery()->getResult()[0]['nr'] > 0;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'slug_property' => 'slug',
            'unique' => true,
            'overwrite' => false
        ]);
        $resolver->setRequired([
            'property'
        ]);
    }

    public function getType()
    {
        return 'slug';
    }
}
