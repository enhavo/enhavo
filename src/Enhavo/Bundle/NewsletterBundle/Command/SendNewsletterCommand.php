<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.19
 * Time: 18:25
 */

namespace Enhavo\Bundle\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SendNewsletterCommand extends Command
{
    use ContainerAwareTrait;
    use LockableTrait;

    /**
     * @var NewsletterManager
     */
    private $manager;

    /**
     * @var EntityManager $em
     */
    private $em;

    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * SendNewsletterCommand constructor.
     * @param NewsletterManager $manager
     * @param EntityManager $em
     * @param Logger $logger
     */
    public function __construct(NewsletterManager $manager, EntityManager $em, Logger $logger)
    {
        parent::__construct();
        $this->manager = $manager;
        $this->em = $em;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this
            ->setName('enhavo:newsletter:send')
            ->setDescription('sends a newsletters to its connected receiver')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'The number of emails that should be sent at max', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('Skip sending.. Command is already running in another process.');
            return;
        }

        $limit = $input->getOption('limit');
        $limit = is_numeric($limit) ? intval($limit) : null;

        $newsletters = $this->em->getRepository(Newsletter::class)->findNotSentNewsletters();

        $mailsSent = 0;

        /** @var NewsletterInterface $newsletter */
        foreach ($newsletters as $newsletter) {
            if ($mailsSent === $limit) {
                break;
            }
            $output->writeln(sprintf('Start sending newsletter "%s"', $newsletter->getSubject()));
            $mailsSent += $this->manager->send($newsletter, is_numeric($limit) ? $limit - $mailsSent : null);
        }

        $output->writeln('Delivery finished');

        $this->release();
        return Command::SUCCESS;
    }
}
