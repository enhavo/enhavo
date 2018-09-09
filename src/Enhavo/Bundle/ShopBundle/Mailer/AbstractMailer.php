<?php
/**
 * AbstractMailer.php
 *
 * @since 27/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Mailer;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Templating\EngineInterface;
use Swift_Mailer;

abstract class AbstractMailer implements MailerInterface
{
    use ContainerAwareTrait;

    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $translationDomain;

    protected $senderName;

    /**
     * AbstractMailer constructor.
     *
     * @param Swift_Mailer $mailer
     * @param EngineInterface $templateEngine
     * @param array $config
     */
    public function __construct(Swift_Mailer $mailer, EngineInterface $templateEngine, $config)
    {
        $this->mailer = $mailer;
        $this->templateEngine = $templateEngine;

        $this->template = $config['template'];
        $this->subject = $config['subject'];
        $this->from = $config['from'];
        $this->translationDomain = $config['translationDomain'];
        $this->senderName = $config['sender_name'];
    }

    protected function render($template, $parameters)
    {
        return $this->templateEngine->render($template, $parameters);
    }

    protected function resolveTemplate($template)
    {
        if($template === null) {
            return $this->template;
        }
        return $template;
    }

    /**
     * @return \Swift_Message
     */
    protected function createMessage()
    {
        return \Swift_Message::newInstance();
    }

    protected function send(\Swift_Message $message)
    {
        $this->mailer->send($message);
    }

    protected function getTranslator()
    {
        return $this->container->get('translator.default');
    }
}