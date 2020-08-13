<?php
/**
 * RecipientMailer.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Mailer;

use Enhavo\Bundle\ContactBundle\Configuration\ConfigurationFactory;
use Enhavo\Bundle\ContactBundle\Model\ContactInterface;
use Symfony\Component\Templating\EngineInterface;
use Enhavo\Bundle\ContactBundle\Configuration\Configuration;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactMailer
{
    /**
     * @var ConfigurationFactory
     */
    private $configurationFactory;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(
        ConfigurationFactory $configurationFactory,
        EngineInterface $templating,
        TranslatorInterface $translator,
        \Swift_Mailer $mailer
    ) {
        $this->configurationFactory = $configurationFactory;
        $this->templating = $templating;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    public function send($name, ContactInterface $model)
    {
        $configuration = $this->configurationFactory->create($name);

        $text = $this->templating->render($configuration->getRecipientTemplate(), [
            'data' => $model
        ]);

        $subject = $this->translator->trans(
            $configuration->getSubject(),
            [],
            $configuration->getTranslationDomain()
        );

        $message = new \Swift_Message();
        $message->setSubject($subject)
            ->setFrom($configuration->getFrom(), $configuration->getSenderName())
            ->setReplyTo($model->getEmail())
            ->setTo($configuration->getRecipient())
            ->setBody($text, 'text/html');

        $this->mailer->send($message);

        if($configuration->isConfirmMail()) {
            $this->sendConfirmMail($configuration, $model);
        }
    }

    private function sendConfirmMail(Configuration $configuration, ContactInterface $model)
    {
        $text = $this->templating->render($configuration->getConfirmTemplate(), [
            'data' => $model
        ]);

        $subject = $this->translator->trans(
            $configuration->getSubject(),
            [],
            $configuration->getTranslationDomain()
        );

        $message = new \Swift_Message();
        $message->setSubject($subject)
            ->setFrom($configuration->getFrom(), $configuration->getSenderName())
            ->setTo($model->getEmail())
            ->setBody($text, 'text/html');

        $this->mailer->send($message);
    }
}
