<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-22
 * Time: 23:31
 */

namespace Enhavo\Bundle\CommentBundle\Controller;

use Enhavo\Bundle\CommentBundle\Comment\CommentManager;
use Symfony\Component\HttpFoundation\Request;

class CommentController
{
    /**
     * @var CommentManager
     */
    private $commentManager;

    /**
     * @param Request $request
     */
    public function createAction(Request $request)
    {
        //$this->commentManager->
    }
}
