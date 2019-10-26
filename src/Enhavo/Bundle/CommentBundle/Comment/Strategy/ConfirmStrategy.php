<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-25
 * Time: 11:55
 */

namespace Enhavo\Bundle\CommentBundle\Comment\Strategy;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\CommentBundle\Comment\PublishStrategyInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Enhavo\Bundle\CommentBundle\Repository\CommentRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class ConfirmStrategy implements PublishStrategyInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var TemplateManager
     */
    private $templateManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * ConfirmStrategy constructor.
     * @param \Swift_Mailer $mailer
     * @param Environment $twig
     * @param TemplateManager $templateManager
     * @param TranslatorInterface $translator
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        \Swift_Mailer $mailer,
        Environment $twig,
        TemplateManager $templateManager,
        TranslatorInterface $translator,
        CommentRepository $commentRepository
    ) {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->templateManager = $templateManager;
        $this->translator = $translator;
        $this->commentRepository = $commentRepository;
    }

    public function preCreate(CommentInterface $comment, array $options): void
    {

    }

    protected function getMailerTo($options)
    {
        return $options['mailer_to'];
    }

    protected function getSubject($options)
    {
        return $this->translator->trans($options['mailer_subject'], [], $options['translation_domain']);
    }

    public function postCreate(CommentInterface $comment, array $options): void
    {
        $template = $this->templateManager->getTemplate('mail/comment/confirm.html.twig');
        $pendingComments = $this->getPendingComments($comment);

        $content = $this->twig->render($template, [
            'comment' => $comment,
            'pendingComments' => $pendingComments
        ]);

        $message = new \Swift_Message();
        $message->setSubject($this->getSubject($options));
        $message->setContentType('text/html');
        $message->setCharset('utf-8');
        $message->setBody($content);
        $message->setTo($this->getMailerTo($options));

        $this->mailer->send($message);
    }

    private function getPendingComments(CommentInterface $newComment): array
    {
        $pendingComments = $this->commentRepository->findBy([
            'state' => CommentInterface::STATE_PENDING
        ]);

        $comments = [];
        foreach($pendingComments as $comment) {
            if($newComment !== $pendingComments) {
                $comments[] = $comment;
            }
        }
        return $comments;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mailer_subject' => 'comment.label.confirm.subject',
            'translation_domain' => 'EnhavoCommentBundle',
        ]);

        $resolver->setRequired(['mailer_to']);
    }
}
