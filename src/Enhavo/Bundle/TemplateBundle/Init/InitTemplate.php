<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepository;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Template\TemplateManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class InitTemplate implements InitInterface
{
    use ContainerAwareTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * @var Factory
     */
    private $templateFactory;

    /**
     * @var EntityRepository
     */
    private $templateRepository;

    /** @var ResourceManager */
    private $resourceManager;

    /**
     * InitTemplate constructor.
     */
    public function __construct(EntityManagerInterface $em, TemplateManager $templateManager, Factory $templateFactory, EntityRepository $templateRepository, ResourceManager $resourceManager)
    {
        $this->em = $em;
        $this->templateManager = $templateManager;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
        $this->resourceManager = $resourceManager;
    }

    public function init(Output $io)
    {
        $templates = $this->templateManager->getTemplates();

        /** @var Template[] $currentTemplates */
        $currentTemplates = $this->templateRepository->findAll();
        foreach ($templates as $code => $template) {
            $exists = false;
            foreach ($currentTemplates as $currentTemplate) {
                if ($currentTemplate->getCode() === $code) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                /* @var Template $newTemplate */
                $io->writeln(sprintf('Add template "%s"', $code));
                $newTemplate = $this->templateFactory->createByTemplate($template);
                $newTemplate->setCode($code);
                $this->resourceManager->save($newTemplate);
            }
        }

        foreach ($currentTemplates as $currentTemplate) {
            if (!array_key_exists($currentTemplate->getCode(), $templates)) {
                $io->writeln(sprintf('Remove template "%s"', $currentTemplate->getCode()));
                $this->resourceManager->delete($currentTemplate);
                $this->em->remove($currentTemplate);
            }
        }
    }

    public function getType()
    {
        return 'template';
    }
}
