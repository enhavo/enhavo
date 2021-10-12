<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-11
 * Time: 01:47
 */

namespace Enhavo\Bundle\TemplateBundle\Router;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\RoutingBundle\Exception\UrlResolverException;
use Enhavo\Bundle\RoutingBundle\Router\AbstractStrategy;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TemplateStrategy extends AbstractStrategy
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    public function generate($resource , $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH, $options = [])
    {
        $template = $this->repository->findOneBy([
            'code' => $options['template']
        ]);

        if(!$template instanceof Template) {
            throw new UrlResolverException(sprintf('The template to generate url for class "%s" not exists. Maybe you forgot to run enhavo:init or didn\'t add the template "%s" to config', get_class($resource), $options['template']));
        }

        $slug = $this->getProperty($resource, 'slug');
        $parameters = array_merge($parameters, ['slug' => $slug]);
        return $this->getRouter()->generate($template->getRoute()->getName(), $parameters, $referenceType);
    }

    public function getType()
    {
        return 'template';
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        parent::configureOptions($optionsResolver);
        $optionsResolver->setRequired([
            'template'
        ]);
    }
}
