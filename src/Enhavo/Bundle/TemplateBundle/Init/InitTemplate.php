<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-07-11
 * Time: 11:50
 */

namespace Enhavo\Bundle\TemplateBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\TemplateBundle\Entity\Template;
use Enhavo\Bundle\TemplateBundle\Factory\TemplateFactory;
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
     * @var Factory
     */
    private $templateRepository;

    /**
     * InitTemplate constructor.
     * @param EntityManagerInterface $em
     * @param TemplateManager $templateManager
     * @param TemplateFactory $templateFactory
     * @param EntityRepository $templateRepository
     */
    public function __construct(EntityManagerInterface $em, TemplateManager $templateManager, TemplateFactory $templateFactory, EntityRepository $templateRepository)
    {
        $this->em = $em;
        $this->templateManager = $templateManager;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
    }

    public function init(Output $io)
    {
        $templates = $this->templateManager->getTemplates();

        /** @var Template[] $currentTemplates */
        $currentTemplates = $this->templateRepository->findAll();
        foreach($templates as $code => $template) {
            $exists = false;
            foreach($currentTemplates as $currentTemplate) {
                if($currentTemplate->getCode() === $code) {
                    $exists = true;
                    break;
                }
            }
            if(!$exists) {
                /** @var Template $newTemplate */
                $newTemplate = $this->templateFactory->createByTemplate($template);
                $newTemplate->setCode($code);
                $io->writeln(sprintf('Add template "%s"', $code));
                $this->em->persist($newTemplate);
            }
        }

        foreach($currentTemplates as $currentTemplate) {
            if(!array_key_exists($currentTemplate->getCode(), $templates)) {
                $io->writeln(sprintf('Remove template "%s"', $currentTemplate->getCode()));
                $this->em->remove($currentTemplate);
            }
        }

        $this->em->flush();
    }

    public function getType()
    {
        return 'template';
    }
}
