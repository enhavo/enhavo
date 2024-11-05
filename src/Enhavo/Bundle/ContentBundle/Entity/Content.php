<?php
/**
 * Content.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ContentBundle\Entity;

use Enhavo\Bundle\AppBundle\Model\Timestampable;
use Enhavo\Bundle\AppBundle\Model\TimestampableTrait;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\RoutingBundle\Model\Slugable;
use Enhavo\Bundle\ContentBundle\Content\Publishable;
use Enhavo\Bundle\ContentBundle\Content\PublishableTrait;
use Enhavo\Bundle\ContentBundle\Sitemap\SitemapInterface;
use Enhavo\Bundle\MediaBundle\Model\FileInterface;

abstract class Content implements Publishable, Routeable, Slugable, SitemapInterface, Timestampable
{
    use PublishableTrait;
    use TimestampableTrait;

    protected ?int $id = null;
    protected ?string $title = null;
    protected ?string $slug = null;
    protected ?string $metaDescription = null;
    protected ?string $pageTitle = null;
    protected bool $noIndex = false;
    protected bool $noFollow = false;
    protected ?FileInterface $openGraphImage = null;
    protected ?string $openGraphTitle = null;
    protected ?string $openGraphDescription = null;
    protected ?RouteInterface $route = null;
    protected ?string $canonicalUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setTitle(?string $title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug)
    {
        $this->slug = $slug;
    }

    public function setMetaDescription(?string $metaDescription)
    {
        $this->metaDescription = $metaDescription;
    }

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setPageTitle(?string $pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    public function getPageTitle(): ?string
    {
        return $this->pageTitle;
    }

    public function isNoIndex(): bool
    {
        return $this->noIndex;
    }

    public function setNoIndex(bool $noIndex)
    {
        $this->noIndex = $noIndex;
    }

    public function isNoFollow(): bool
    {
        return $this->noFollow;
    }

    public function setNoFollow(bool $noFollow)
    {
        $this->noFollow = $noFollow;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }

    public function setRoute(?RouteInterface $route)
    {
        $this->route = $route;
    }

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setOpenGraphTitle(?string $openGraphTitle): void
    {
        $this->openGraphTitle = $openGraphTitle;
    }

    public function getOpenGraphTitle(): ?string
    {
        return $this->openGraphTitle;
    }

    public function setOpenGraphDescription(?string $openGraphDescription)
    {
        $this->openGraphDescription = $openGraphDescription;

        return $this;
    }

    public function getOpenGraphDescription(): ?string
    {
        return $this->openGraphDescription;
    }

    public function setOpenGraphImage(?FileInterface $openGraphImage = null)
    {
        $this->openGraphImage = $openGraphImage;
    }

    public function getOpenGraphImage(): ?FileInterface
    {
        return $this->openGraphImage;
    }

    public function __toString()
    {
        return (string)$this->getTitle();
    }

    public function getCanonicalUrl(): ?string
    {
        return $this->canonicalUrl;
    }

    public function setCanonicalUrl(?string $canonicalUrl): void
    {
        $this->canonicalUrl = $canonicalUrl;
    }
}
