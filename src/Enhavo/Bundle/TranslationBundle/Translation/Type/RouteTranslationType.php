<?php
/**
 * TranslationTableStrategy.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Type;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Enhavo\Bundle\TranslationBundle\Model\TranslationTableData;
use Enhavo\Bundle\TranslationBundle\Translator\LocaleResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Route\RouteGuesser;

class RouteTranslationType extends TranslationTableStrategy
{
    /**
     * @var RouteGuesser
     */
    protected $routeGuesser;

    /**
     * @var array
     */
    protected $updateRouteMap = [];

    public function __construct($locales, LocaleResolver $localeResolver, EntityManagerInterface $em, $routeGuesser)
    {
        parent::__construct($locales, $localeResolver, $em);
        $this->routeGuesser = $routeGuesser;
    }

    public function addTranslationData($entity, Property $property, $data, Metadata $metadata)
    {
        parent::addTranslationData($entity, $property, $data, $metadata);

        $oid = spl_object_hash($entity);
        if(!isset($this->updateRouteMap[$oid])) {
            $this->updateRouteMap[$oid] = [];
        }
        $this->updateRouteMap[$oid][] = [
            'entity' => $entity
        ];
    }

    public function storeTranslationData($entity, Metadata $metadata)
    {
        $oid = spl_object_hash($entity);
        if(!isset($this->translationDataMap[$oid])) {
            return;
        }

        if(!$entity instanceof Route) {
            return;
        }

        $translationData = $this->translationDataMap[$oid];

        /** @var TranslationTableData $data */
        foreach($translationData as $data) {
            foreach($data->getValues() as $locale => $value) {
                $translationRoute = $this->getEntityManager()->getRepository('EnhavoTranslationBundle:TranslationRoute')->findOneBy([
                    'type' => $entity->getType(),
                    'typeId' => $entity->getTypeId(),
                    'locale' => $locale
                ]);

                if($translationRoute === null || $entity->getType() === null || $entity->getTypeId() === null) {
                    $route = new Route();
                    $route->setContent($entity->getContent());

                    $translationRoute = new TranslationRoute();
                    $translationRoute->setLocale($locale);
                    $translationRoute->setRoute($route);

                    $this->updateRefIds[] = $translationRoute;
                }

                /** @var Route $route */
                $route = $translationRoute->getRoute();
                $route->setStaticPrefix($value);
                $translationRoute->setPath($value);
            }
        }
        unset($this->translationDataMap[$oid]);
    }

    public function deleteTranslationData($entity, Metadata $metadata)
    {
        if(!$entity instanceof Route) {
            return;
        }

        $translationRoutes = $this->getEntityManager()->getRepository('EnhavoTranslationBundle:TranslationRoute')->findBy([
            'type' => $entity->getType(),
            'typeId' => $entity->getTypeId()
        ]);

        foreach($translationRoutes as $translationRoute) {
            $this->getEntityManager()->remove($translationRoute);
        }
    }

    public function getTranslationData($entity, Property $property, Metadata $metadata)
    {
        $data = [];

        foreach($this->locales as $locale) {
            $data[$locale] = null;
        }

        if(!$entity instanceof Route) {
            return $data;
        }

        $translationRoutes = $this->getEntityManager()->getRepository('EnhavoTranslationBundle:TranslationRoute')->findBy([
            'type' => $entity->getType(),
            'typeId' => $entity->getTypeId()
        ]);

        foreach($this->locales as $locale) {
            if($locale === $this->localeResolver->getLocale()) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $value = $accessor->getValue($entity, $property->getName());
                $data[$locale] = $value;
                continue;
            }
            $value = null;
            foreach($translationRoutes as $translationRoute) {
                if($translationRoute->getLocale() === $locale) {
                    $value = $translationRoute->getRoute()->getStaticPrefix();
                    break;
                }
            }
            $data[$locale] = $value;
        }

        return $data;
    }

    public function postFlush()
    {
        /**
         * @var string $key
         * @var TranslationRoute $translationRoute
         */
        foreach($this->updateRefIds as $key => $translationRoute) {
            /** @var Route $route */
            $route = $translationRoute->getRoute();
            $this->getEntityManager()->persist($route);
            $this->container->get('enhavo_app.route_manager')->update($route);
            $this->getEntityManager()->flush();
            $this->container->get('enhavo_app.route_manager')->update($route);

            $translationRoute->setType($route->getType());
            $translationRoute->setTypeId($route->getTypeId());

            $this->getEntityManager()->persist($translationRoute);
            unset($this->updateRefIds[$key]);
        }

        $this->getEntityManager()->flush();

        //update path if the aren't set or has not correct country prefix
        foreach($this->updateRouteMap as $entities) {
            foreach($entities as $updateSlug) {
                /** @var Route $entity */
                $entity = $updateSlug['entity'];

                $translationRoutes = $this->getEntityManager()->getRepository('EnhavoTranslationBundle:TranslationRoute')->findBy([
                    'type' => $entity->getType(),
                    'typeId' => $entity->getTypeId(),
                ]);

                /** @var TranslationRoute $translationRoute */
                if($translationRoutes) {
                    foreach($translationRoutes as $translationRoute) {
                        if(empty($translationRoute->getPath())) {
                            $slug = Slugifier::slugify($this->getContext($entity->getContent(), $translationRoute->getLocale()));
                            $path = sprintf('/%s/%s', $translationRoute->getLocale(), $slug);
                            $translationRoute->setPath($path);
                            $translationRoute->getRoute()->setStaticPrefix($path);
                        }
                    }
                }

                if(empty($entity->getStaticPrefix())) {
                    $slug = Slugifier::slugify($this->getContext($entity->getContent(), $this->localeResolver->getLocale()));
                    $path = sprintf('/%s/%s', $this->localeResolver->getLocale(), $slug);
                    $entity->setStaticPrefix($path);
                }

                $shouldStartWith = sprintf('/%s/', $this->localeResolver->getLocale());
                $startWith = substr($entity->getStaticPrefix(), 0 , strlen($shouldStartWith));
                if($startWith != $shouldStartWith) {
                    $entity->setStaticPrefix(sprintf('/%s%s', $this->localeResolver->getLocale(), $entity->getStaticPrefix()));
                }
            }
        }

        $this->updateRouteMap = [];
        $this->getEntityManager()->flush();
    }

    protected function getContext($subject, $locale)
    {
        $context = $this->routeGuesser->guessContext($subject);

        if(is_array($context)) {
            $newTitle = null;
            foreach($context as $titleLocale => $value) {
                if(!empty($value) && empty($newTitle)) {
                    $newTitle = $value;
                }
                if($titleLocale === $locale && !empty($value)) {
                    $newTitle = $value;
                }
            }
            if(empty($newTitle)) {
                $newTitle = microtime(true);
            }
            return $newTitle;
        }

        return $context;
    }

    public function getTranslation($entity, Property $property, $locale, Metadata $metadata)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        return $accessor->getValue($entity, $property->getName());
    }
}
