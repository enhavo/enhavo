<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\CommentBundle\Comment;

use Symfony\Component\Form\FormInterface;

class SubmitContext
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var bool
     */
    private $insert;

    /**
     * SubmitContext constructor.
     */
    public function __construct(FormInterface $form, bool $insert)
    {
        $this->form = $form;
        $this->insert = $insert;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function isInsert(): bool
    {
        return $this->insert;
    }
}
