<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 24.05.19
 * Time: 19:01
 */

namespace Enhavo\Bundle\TemplateBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

class Template implements ResourceInterface, Routeable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var NodeInterface
     */
    private $content;

    /**
     * @var Route
     */
    private $route;

    /**
     * @var \Enhavo\Bundle\TemplateBundle\Template\Template
     */
    private $template;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $code string
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set content
     *
     * @param NodeInterface $content
     * @return Template
     */
    public function setContent(NodeInterface $content = null)
    {
        $this->content = $content;
        if($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
        }
        return $this;
    }

    /**
     * Get content
     *
     * @return NodeInterface
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return RouteInterface
     */
    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    /**
     * @param RouteInterface $route
     */
    public function setRoute(?RouteInterface $route): void
    {
        $this->route = $route;
    }

    /**
     * @return \Enhavo\Bundle\TemplateBundle\Template\Template
     */
    public function getTemplate(): \Enhavo\Bundle\TemplateBundle\Template\Template
    {
        return $this->template;
    }

    /**
     * @param \Enhavo\Bundle\TemplateBundle\Template\Template $template
     */
    public function setTemplate(\Enhavo\Bundle\TemplateBundle\Template\Template $template): void
    {
        $this->template = $template;
    }
}
