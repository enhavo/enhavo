<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-26
 * Time: 17:52
 */

namespace Enhavo\Bundle\CommentBundle\Twig;

use Enhavo\Bundle\CommentBundle\Comment\ConfirmUrlGeneratorInterface;
use Enhavo\Bundle\CommentBundle\Model\CommentInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CommentTwigExtension extends AbstractExtension
{
    /**
     * @var ConfirmUrlGeneratorInterface
     */
    private $commentUrlGenerator;

    /**
     * CommentTwigExtension constructor.
     * @param ConfirmUrlGeneratorInterface $commentUrlGenerator
     */
    public function __construct(ConfirmUrlGeneratorInterface $commentUrlGenerator)
    {
        $this->commentUrlGenerator = $commentUrlGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('comment_confirm_url', [$this, 'getCommentConfirmUrl'])
        ];
    }

    public function getCommentConfirmUrl(CommentInterface $comment)
    {
        return $this->commentUrlGenerator->generate($comment);
    }
}
