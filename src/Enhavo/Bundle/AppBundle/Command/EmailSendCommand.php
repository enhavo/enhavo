<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSendCommand extends Command
{
    public function __construct(
        private MailerInterface $mailer,
        private array $mailConfig,
    ) {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:email:send')
            ->setDescription('Debug command for testing email dispatch')
            ->addArgument('from', InputArgument::OPTIONAL, 'From')
            ->addArgument('to', InputArgument::OPTIONAL, 'To')
            ->addArgument('subject', InputArgument::OPTIONAL, 'Subject')
            ->addArgument('body', InputArgument::OPTIONAL, 'Body')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $from = $input->getArgument('from');
        $to = $input->getArgument('to');
        $subject = $input->getArgument('subject');
        $body = $input->getArgument('body');

        if (!$from) {
            $from = $helper->ask($input, $output, new Question('From ['.$this->mailConfig['from'].']: ', $this->mailConfig['from']));
        }
        if (!$to) {
            $to = $helper->ask($input, $output, new Question('To ['.$this->mailConfig['to'].']: ', $this->mailConfig['to']));
        }
        if (!$subject) {
            $subject = $helper->ask($input, $output, new Question('Subject [Testmail]: ', 'Testmail'));
        }
        if (!$body) {
            $body = $helper->ask($input, $output, new Question('Body [Testmail]: ', 'Testmail'));
        }

        $email = new Email();
        $email->from($from);
        $email->to($to);
        $email->subject($subject);
        $email->html($body);

        $this->mailer->send($email);

        return 0;
    }
}
