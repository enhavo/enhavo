<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-06-07
 * Time: 11:50
 */

namespace Enhavo\Bundle\ThemeBundle\Init;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Factory\Factory;
use Enhavo\Bundle\AppBundle\Init\InitInterface;
use Enhavo\Bundle\AppBundle\Init\Output;
use Enhavo\Bundle\AppBundle\Repository\EntityRepository;
use Enhavo\Bundle\ThemeBundle\Model\Entity\Theme;
use Enhavo\Bundle\ThemeBundle\Theme\ThemeManager;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class InitTheme implements InitInterface
{
    use ContainerAwareTrait;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ThemeManager
     */
    private $themeManager;

    /**
     * @var boolean
     */
    private $load;

    /**
     * @var Factory
     */
    private $themeFactory;

    /**
     * @var Factory
     */
    private $themeRepository;

    /**
     * InitTheme constructor.
     * @param EntityManagerInterface $em
     * @param ThemeManager $themeManager
     * @param $load
     * @param Factory $themeFactory
     * @param EntityRepository $themeRepository
     */
    public function __construct(EntityManagerInterface $em, ThemeManager $themeManager, $load, Factory $themeFactory, EntityRepository $themeRepository)
    {
        $this->em = $em;
        $this->themeManager = $themeManager;
        $this->load = $load;
        $this->themeFactory = $themeFactory;
        $this->themeRepository = $themeRepository;
    }

    public function init(Output $io)
    {
        if($this->load) {
            $themes = $this->themeManager->getThemes();

            /** @var Theme[] $currentThemes */
            $currentThemes = $this->themeRepository->findAll();
            foreach($themes as $key => $theme) {
                $exists = false;
                foreach($currentThemes as $currentTheme) {
                    if($currentTheme->getKey() === $key) {
                        $exists = true;
                        break;
                    }
                }
                if(!$exists) {
                    /** @var Theme $newTheme */
                    $newTheme = $this->themeFactory->createNew();
                    $newTheme->setKey($key);
                    $io->writeln(sprintf('Add theme "%s"', $key));
                    $this->em->persist($newTheme);
                }
            }

            foreach($currentThemes as $currentTheme) {
                if(!array_key_exists($currentTheme->getKey(), $themes)) {
                    $io->writeln(sprintf('Remove theme "%s"', $currentTheme->getKey()));
                    $this->em->remove($currentTheme);
                }
            }

            $this->em->flush();
        }

    }

    public function getType()
    {
        return 'theme';
    }
}
