<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
    public function __construct(ConfirmUrlGeneratorInterface $commentUrlGenerator)
    {
        $this->commentUrlGenerator = $commentUrlGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('comment_confirm_url', [$this, 'getCommentConfirmUrl']),
        ];
    }

    public function getCommentConfirmUrl(CommentInterface $comment)
    {
        return $this->commentUrlGenerator->generate($comment);
    }
}
