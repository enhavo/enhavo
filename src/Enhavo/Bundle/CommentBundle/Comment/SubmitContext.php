<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-10-24
 * Time: 13:25
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
     * @var boolean
     */
    private $insert;

    /**
     * SubmitContext constructor.
     * @param FormInterface $form
     * @param bool $insert
     */
    public function __construct(FormInterface $form, bool $insert)
    {
        $this->form = $form;
        $this->insert = $insert;
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        return $this->form;
    }

    /**
     * @return bool
     */
    public function isInsert(): bool
    {
        return $this->insert;
    }
}
