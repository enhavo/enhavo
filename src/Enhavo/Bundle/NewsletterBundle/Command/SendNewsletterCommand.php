<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.19
 * Time: 18:25
 */

namespace Enhavo\Bundle\NewsletterBundle\Command;

use Doctrine\ORM\EntityManager;

use Enhavo\Bundle\NewsletterBundle\Newsletter\NewsletterManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class SendNewsletterCommand extends Command
{
    use ContainerAwareTrait;

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
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, Logger $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:newsletter:send')
            ->setDescription('sends a newsletters to its connected receiver');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->container->get('enhavo.newsletter.newsletter_manager');

        if($this->manager) {
            $receivers = $this->em
                ->getRepository('EnhavoNewsletterBundle:Receiver')
                ->findBy([
                    'sentAt' => null
                ]);

            foreach ($receivers as $key =>  $receiver) {
                $this->logger->info(sprintf('%s prepared receiver found', count($receivers)));
                $this->logger->info(sprintf('Start sending newsletter to %s of %s receivers', $key, count($receivers)));
                try {
                    $newsletter = $receiver->getNewsletter();
                    $this->manager->sendNewsletter($newsletter, $receiver);
                    $newsletter->setSent(true);
                    $newsletter->setSentAt(new \DateTime());
                    $this->em->persist($newsletter);
                } catch (\Exception $e) {
                    $this->logger->info(sprintf('Error! Message: %s', $e->getMessage()));
                }
                $this->em->flush();
                $this->logger->info(sprintf('Newsletter %s of %s successfully sent', $key, count($receivers)));
            }
        }
    }
}
