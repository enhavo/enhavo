<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;
use Enhavo\Bundle\NewsletterBundle\Entity\Newsletter;
use Enhavo\Bundle\NewsletterBundle\Model\NewsletterInterface;
use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendNewsletterCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly NewsletterManager $manager,
        private readonly EntityManager $em,
        private readonly LoggerInterface $logger,
    ) {
        parent::__construct();
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

            return Command::SUCCESS;
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
