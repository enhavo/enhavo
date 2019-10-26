<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-09-06
 * Time: 12:58
 */

namespace Enhavo\Bundle\CommentBundle\Model;


interface ThreadInterface
{
    public function setSubject(?CommentSubjectInterface $subject);

    public function getSubject(): ?CommentSubjectInterface;
}
