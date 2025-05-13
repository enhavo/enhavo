<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\TemplateBundle\Entity;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;

class Template implements Routeable
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
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set content
     *
     * @return Template
     */
    public function setContent(?NodeInterface $content = null)
    {
        $this->content = $content;
        if ($content) {
            $content->setType(NodeInterface::TYPE_ROOT);
            $content->setProperty('content');
            $content->setResource($this);
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

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(?RouteInterface $route): void
    {
        $this->route = $route;
    }

    public function getTemplate(): \Enhavo\Bundle\TemplateBundle\Template\Template
    {
        return $this->template;
    }

    public function setTemplate(\Enhavo\Bundle\TemplateBundle\Template\Template $template): void
    {
        $this->template = $template;
    }
}
