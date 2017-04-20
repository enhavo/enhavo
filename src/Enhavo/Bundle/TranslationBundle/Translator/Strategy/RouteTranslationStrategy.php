<?php
/**
 * TranslationTableStrategy.php
 *
 * @since 27/11/16
 * @author gseidel
 */

namespace Enhavo\Bundle\TranslationBundle\Translator\Strategy;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\TranslationBundle\Entity\Translation;
use Enhavo\Bundle\TranslationBundle\Entity\TranslationRoute;
use Enhavo\Bundle\TranslationBundle\Metadata\Metadata;
use Enhavo\Bundle\TranslationBundle\Metadata\Property;
use Enhavo\Bundle\TranslationBundle\Translator\TranslationStrategyInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Enhavo\Bundle\TranslationBundle\Repository\TranslationRepository;

class RouteTranslationStrategy extends TranslationTableStrategy
{
    use ContainerAwareTrait;

//    protected function preSave()
//    {
//        if($subject instanceof Routeable) {
//            /** @var Route $route */
//            $route = $subject->getRoute();
//            if($route instanceof Route) {
//                $staticPrefixes = $route->getStaticPrefix();
//                if(is_array($staticPrefixes)) {
//                    foreach($staticPrefixes as $locale => $value) {
//                        if(empty($value)) {
//                            $staticPrefixes[$locale] = sprintf('/%s/%s', $locale, $this->slugify($this->getContext($subject, $locale)));
//                        }
//                    }
//                    $route->setStaticPrefix($staticPrefixes);
//                }
//            }
//        }
//    }
//
//    protected function getContext($subject, $locale)
//    {
//        $context = $this->routeGuesser->guessContext($subject);
//
//        if(is_array($context)) {
//            $newTitle = null;
//            foreach($context as $titleLocale => $value) {
//                if(!empty($value) && empty($newTitle)) {
//                    $newTitle = $value;
//                }
//                if($titleLocale === $locale && !empty($value)) {
//                    $newTitle = $value;
//                }
//            }
//            if(empty($newTitle)) {
//                $newTitle = microtime(true);
//            }
//            return $newTitle;
//        }
//
//        return $context;
//    }

//    public function storeTranslationData($entity, Metadata $metadata)
//    {
//        $accessor = PropertyAccess::createPropertyAccessor();
//        $values = $accessor->getValue($entity, $property->getName());
//        if(is_array($values)) {
//            $value = $this->storeValues($values, $entity, $property, $metadata);
//            $accessor->setValue($entity, $property->getName(), $value);
//        }
//    }
//
//    protected function getEntityManager()
//    {
//        return $this->container->get('doctrine.orm.default_entity_manager');
//    }
//
//    protected function storeValues(array $values, $entity, Property $property, Metadata $metadata)
//    {
//        $return = null;
//        if(!$entity instanceof Route) {
//            return null;
//        }
//
//        foreach($values as $locale => $value) {
//            if($this->defaultLocale === $locale) {
//                $return = $value;
//            } else {
//
//                $translationRoute = $this->getRepository('EnhavoTranslationBundle:TranslationRoute')->findOneBy([
//                    'type' => $entity->getType(),
//                    'typeId' => $entity->getTypeId()
//                ]);
//
//                if($translationRoute === null || $entity->getType() === null || $entity->getTypeId() === null) {
//                    $route = new Route();
//                    $route->setContent($entity->getContent());
//
//                    $translationRoute = new TranslationRoute();
//                    $translationRoute->setLocale($locale);
//                    $translationRoute->setRoute($route);
//
//                    $this->updateRefIds[] = $translationRoute;
//                }
//
//                /** @var Route $route */
//                $route = $translationRoute->getRoute();
//                $route->setStaticPrefix($value);
//                $translationRoute->setPath($value);
//            }
//        }
//
//        return $return;
//    }
//
//    protected function getRepository($repository)
//    {
//        return $this->getEntityManager()->getRepository($repository);
//    }
//
//    public function delete($entity, Metadata $metadata)
//    {
//        if(!$entity instanceof Route) {
//            return;
//        }
//
//        $translationRoutes = $this->getRepository('EnhavoTranslationBundle:TranslationRoute')->findBy([
//            'type' => $entity->getType(),
//            'typeId' => $entity->getTypeId()
//        ]);
//
//        foreach($translationRoutes as $translationRoute) {
//            $this->getEntityManager()->remove($translationRoute);
//        }
//    }
//
//    public function getTranslations($entity, Metadata $metadata, Property $property)
//    {
//        $data = [];
//
//        foreach($this->locales as $locale) {
//            $data[$locale] = null;
//        }
//
//        if(!$entity instanceof Route) {
//            return $data;
//        }
//
//        $translationRoutes = $this->getRepository('EnhavoTranslationBundle:TranslationRoute')->findBy([
//            'type' => $entity->getType(),
//            'typeId' => $entity->getTypeId()
//        ]);
//
//        foreach($this->locales as $locale) {
//            if($locale === $this->defaultLocale) {
//                $accessor = PropertyAccess::createPropertyAccessor();
//                $value = $accessor->getValue($entity, $property->getName());
//                $data[$locale] = $value;
//                continue;
//            }
//            $value = null;
//            foreach($translationRoutes as $translationRoute) {
//                if($translationRoute->getLocale() === $locale) {
//                    $value = $translationRoute->getRoute()->getStaticPrefix();
//                    break;
//                }
//            }
//            $data[$locale] = $value;
//        }
//
//        return $data;
//    }
//
//    public function postFlush()
//    {
//        /**
//         * @var string $key
//         * @var TranslationRoute $translationRoute
//         */
//        foreach($this->updateRefIds as $key => $translationRoute) {
//            /** @var Route $route */
//            $route = $translationRoute->getRoute();
//            $this->getEntityManager()->persist($route);
//            $this->container->get('enhavo_app.route_manager')->update($route);
//            $this->getEntityManager()->flush();
//            $this->container->get('enhavo_app.route_manager')->update($route);
//
//            $translationRoute->setType($route->getType());
//            $translationRoute->setTypeId($route->getTypeId());
//
//            $this->getEntityManager()->persist($translationRoute);
//            unset($this->updateRefIds[$key]);
//        }
//        $this->getEntityManager()->flush();
//    }
}