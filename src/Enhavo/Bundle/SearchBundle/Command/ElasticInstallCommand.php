<?php
namespace Enhavo\Bundle\SearchBundle\Command;

use Enhavo\Bundle\SearchBundle\Elastic\ElasticManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ElasticInstallCommand extends Command
{
    /** @var ElasticManager */
    private $elasticManager;

    /** @var string */
    private $version;

    /**
     * ElasticInstallCommand constructor.
     * @param ElasticManager $elasticManager
     */
    public function __construct(ElasticManager $elasticManager, $version)
    {
        parent::__construct();
        $this->elasticManager = $elasticManager;
        $this->version = $version;
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:search:elastic:install')
            ->setDescription('Install elastic search locally')
            ->addArgument('version', InputArgument::OPTIONAL, 'Elasticsearch version', $this->version)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->elasticManager->existsInstallation()) {
            while (true) {
                $questionHelper = $this->getHelper('question');
                $question = new Question('elasticsearch directory exists, overwrite? [y/n]', 'n');
                $option = $questionHelper->ask($input, $output, $question);

                if (strtolower($option) === 'n') {
                    return Command::SUCCESS;
                } elseif (strtolower($option) === 'y') {
                    $this->download($input, $output);
                    return Command::SUCCESS;
                }
            }
        }

        $this->download($input, $output);
        return Command::SUCCESS;
    }

    private function download(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument('version');
        if ($version === null) {
            $version = $this->version;
        }
        $output->writeln(sprintf('Download file version %s', $version));
        $progress = new ProgressBar($output, 100);
        $resource = stream_context_create(array(), array('notification' => function ($notificationCode, $severity, $message, $messageCode, $bytesTransferred, $bytesMax) use ($output, $progress) {
            switch ($notificationCode) {
                case STREAM_NOTIFY_PROGRESS:
                    $percent = intval(ceil(($bytesTransferred/$bytesMax)*100));
                    $progress->setProgress($percent);
                    break;
            }
        }));

        $this->elasticManager->install($version, $resource);
        $progress->finish();
    }
}
