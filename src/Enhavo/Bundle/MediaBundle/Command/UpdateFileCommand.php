<?php
/**
 * UpdateFileCommand.php
 *
 * @since 28/06/15
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\AppBundle\Util\TokenGeneratorInterface;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFileCommand extends Command
{
    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UpdateFileCommand constructor.
     * @param MediaManager $mediaManager
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $em
     */
    public function __construct(
        MediaManager $mediaManager,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $em
    ) {
        $this->mediaManager = $mediaManager;
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:update')
            ->setDescription('Delete files from system without meta')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $this->mediaManager->findBy();

        foreach($files as $file)
        {
            if(!$file->getMd5Checksum()) {
                $file->setMd5Checksum(md5($file->getContent()->getContent()));
            }

            if(!$file->getToken()) {
                $file->setToken($this->tokenGenerator->generateToken(10));
            }
        }

        $this->em->flush();
        $output->writeln('Files updated');
        return Command::SUCCESS;
    }
}
