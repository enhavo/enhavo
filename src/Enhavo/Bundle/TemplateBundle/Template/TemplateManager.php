<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Template;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\TemplateBundle\Entity\ResourceBlock;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;

class TemplateManager
{
    use ContainerAwareTrait;

    /**
     * @var Template[]
     */
    private $templates = [];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct($configuration, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $this->em = $em;

        foreach ($configuration as $key => $value) {
            $template = new Template();
            $this->templates[$key] = $template;

            $template->setKey($key);
            $template->setLabel($value['label']);
            $template->setRepository($value['repository']);
            $template->setTranslationDomain($value['translation_domain']);
            $template->setTemplate($value['template']);
            $template->setResourceTemplate($value['resource_template']);
            $template->setName($translator->trans($template->getLabel(), [], $template->getTranslationDomain()));
        }
    }

    /**
     * @return Template
     */
    public function getTemplate($key)
    {
        return $this->templates[$key];
    }

    public function getResource(Template $template, Request $request)
    {
        $parameters = $request->attributes->get('_route_params');
        $repository = $this->getRepository($template->getRepository());
        $content = $repository->findOneBy($parameters);

        return $content;
    }

    /**
     * @return EntityRepository
     */
    private function getRepository($name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        }

        return $this->em->getRepository($name);
    }

    public function injectTemplate(\Enhavo\Bundle\TemplateBundle\Entity\Template $templateEntity, $template)
    {
        $nodes = $templateEntity->getContent();
        foreach ($nodes->getDescendants() as $node) {
            $block = $node->getBlock();
            if ($block instanceof ResourceBlock) {
                $block->setTemplate($template);
            }
        }
    }

    public function getTemplates()
    {
        return $this->templates;
    }
}
