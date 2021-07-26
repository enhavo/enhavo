<?php
/**
 * CleanUpCommand.php
 *
 * @since 13/11/17
 * @author gseidel
 */

namespace Enhavo\Bundle\MediaBundle\Command;

use Enhavo\Bundle\MediaBundle\Entity\Format;
use Enhavo\Bundle\MediaBundle\Exception\FormatException;
use Enhavo\Bundle\MediaBundle\Media\FormatManager;
use Enhavo\Bundle\MediaBundle\Media\MediaManager;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class RefreshFormatCommand extends Command
{
    /**
     * @var RepositoryInterface
     */
    private $formatRepository;

    /**
     * @var RepositoryInterface
     */
    private $fileRepository;

    /**
     * @var MediaManager
     */
    private $mediaManager;

    /**
     * @var FormatManager
     */
    private $formatManager;

    /**
     * RefreshFormatCommand constructor.
     *
     * @param RepositoryInterface $formatRepository
     * @param RepositoryInterface $fileRepository
     * @param MediaManager $mediaManager
     * @param FormatManager $formatManager
     */
    public function __construct(
        RepositoryInterface $formatRepository,
        RepositoryInterface $fileRepository,
        MediaManager $mediaManager,
        FormatManager $formatManager
    ) {
        $this->formatRepository = $formatRepository;
        $this->fileRepository = $fileRepository;
        $this->mediaManager = $mediaManager;
        $this->formatManager = $formatManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:media:refresh-format')
            ->setDescription('Recreate Format')
            ->addOption('format', 'format', InputOption::VALUE_OPTIONAL, 'format to refresh')
            ->addOption('id', 'id', InputOption::VALUE_OPTIONAL, 'refresh only id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Refresh formats:');

        $options = [];

        $format = $input->getOption('format');
        if(!empty($format)) {
            $options['name'] = $format;
        }

        $id = $input->getOption('id');
        if(!empty($id)) {
            $id = $input->getOption('id');
            $options['file'] = $this->fileRepository->find($id);
        }

        /** @var Format[] $formats */
        $formats = $this->formatRepository->findBy($options);

        $progressBar = new ProgressBar($output, count($formats));
        $progressBar->start();

        $notExistingFormats = [];
        $errors = [];
        foreach($formats as $format) {
            if ($this->formatManager->existsFormat($format->getName())) {
                try {
                    $this->formatManager->applyFormat($format->getFile(), $format->getName());
                } catch (FormatException $e) {
                    $errors[] = [
                        'message' => $e->getMessage(),
                        'format' => $format->getName(),
                        'id' => $format->getId()
                    ];
                }
            } else {
                if (!in_array($format->getName(), $notExistingFormats)) {
                    $notExistingFormats[] = $format->getName();
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        $output->writeln('');

        foreach ($notExistingFormats as $formatName) {
            $output->writeln(sprintf('<comment>Warning: Format "%s" not exists</comment>', $formatName));
        }

        foreach ($errors as $error) {
            $output->writeln(sprintf('<error>Error on format "%s" with id "%s" : %s</error>', $error['format'], $error['id'], $error['message']));
        }

        $output->writeln('<info>Refreshing finished</info>');

        if (count($errors)) {
            return 1;
        }

        return 0;
    }
}
