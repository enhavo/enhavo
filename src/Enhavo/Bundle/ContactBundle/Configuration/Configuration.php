<?php
/**
 * Configuration.php
 *
 * @since 13/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContactBundle\Configuration;


class Configuration
{
    /**
     * @var
     */
    private $name;

    /**
     * @var string
     */
    private $model;

    /**
     * @var string
     */
    private $form;

    /**
     * @var string
     */
    private $formTemplate;

    /**
     * @var string
     */
    private $recipientTemplate;

    /**
     * @var string
     */
    private $confirmTemplate;

    /**
     * @var string
     */
    private $pageTemplate;

    /**
     * @var string
     */
    private $messageSuccess;

    /**
     * @var string
     */
    private $messageInvalid;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $senderName;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $translationDomain;

    /**
     * @var boolean
     */
    private $confirmMail;

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return string
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param string $form
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * @return string
     */
    public function getFormTemplate()
    {
        return $this->formTemplate;
    }

    /**
     * @param string $formTemplate
     */
    public function setFormTemplate($formTemplate)
    {
        $this->formTemplate = $formTemplate;
    }

    /**
     * @return string
     */
    public function getRecipientTemplate()
    {
        return $this->recipientTemplate;
    }

    /**
     * @param string $recipientTemplate
     */
    public function setRecipientTemplate($recipientTemplate)
    {
        $this->recipientTemplate = $recipientTemplate;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * @param string $translationDomain
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
    }

    /**
     * @return boolean
     */
    public function isConfirmMail()
    {
        return $this->confirmMail;
    }

    /**
     * @param boolean $confirmMail
     */
    public function setConfirmMail($confirmMail)
    {
        $this->confirmMail = $confirmMail;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return string
     */
    public function getConfirmTemplate()
    {
        return $this->confirmTemplate;
    }

    /**
     * @param string $confirmTemplate
     */
    public function setConfirmTemplate($confirmTemplate)
    {
        $this->confirmTemplate = $confirmTemplate;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param string $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }

    /**
     * @param string $pageTemplate
     */
    public function setPageTemplate($pageTemplate)
    {
        $this->pageTemplate = $pageTemplate;
    }

    /**
     * @return string
     */
    public function getMessageSuccess()
    {
        return $this->messageSuccess;
    }

    /**
     * @param string $messageSuccess
     */
    public function setMessageSuccess($messageSuccess)
    {
        $this->messageSuccess = $messageSuccess;
    }

    /**
     * @return string
     */
    public function getMessageInvalid()
    {
        return $this->messageInvalid;
    }

    /**
     * @param string $messageInvalid
     */
    public function setMessageInvalid($messageInvalid)
    {
        $this->messageInvalid = $messageInvalid;
    }
}