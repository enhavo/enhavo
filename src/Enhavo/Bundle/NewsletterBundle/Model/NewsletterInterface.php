<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-29
 * Time: 18:19
 */

namespace Enhavo\Bundle\NewsletterBundle\Model;

use Enhavo\Bundle\BlockBundle\Model\NodeInterface;

interface NewsletterInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set title
     *
     * @param string $title
     *
     * @return NewsletterInterface
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getSlug();

    /**
     * @param string $slug
     * @return NewsletterInterface
     */
    public function setSlug($slug);

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return NewsletterInterface
     */
    public function setSubject($subject);

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject();

    /**
     * @param NodeInterface $content
     * @return void
     */
    public function setContent(NodeInterface $content);

    /**
     * @return NodeInterface
     */
    public function getContent();
}
