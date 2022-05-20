<?php
/**
 * Product.php
 *
 * @since 16/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\ArticleBundle\Entity\Article;
use Enhavo\Bundle\RoutingBundle\Entity\Route;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Model\Routeable;
use Enhavo\Bundle\ShopBundle\Model\ProductAccessTrait;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Enhavo\Bundle\TaxonomyBundle\Model\TermInterface;
use Sylius\Component\Product\Model\Product as SyliusProduct;

class Product extends SyliusProduct implements ProductInterface, Routeable
{
    private ?string $lengthUnit;
    private ?string $volumeUnit;
    private ?string $weightUnit;
    use ProductAccessTrait;

    private ?Route $route;
    private Collection $categories;
    private Collection $tags;

    public function __construct()
    {
        parent::__construct();
        $this->pictures = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString(): string
    {
        if($this->title === null) {
            return '';
        }
        return $this->title;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(RouteInterface $route): ProductInterface
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->getTitle();
    }

    public function setName(?string $name): void
    {
        $this->setTitle($name);
    }


    /**
     * @return string
     */
    public function getLengthUnit(): ?string
    {
        return $this->lengthUnit;
    }

    /**
     * @param string $lengthUnit
     * @return self
     */
    public function setLengthUnit(?string $lengthUnit): ProductInterface
    {
        $this->lengthUnit = $lengthUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getVolumeUnit(): ?string
    {
        return $this->volumeUnit;
    }

    /**
     * @param string $volumeUnit
     * @return self
     */
    public function setVolumeUnit(?string $volumeUnit): ProductInterface
    {
        $this->volumeUnit = $volumeUnit;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeightUnit(): ?string
    {
        return $this->weightUnit;
    }

    /**
     * @param string $weightUnit
     * @return self
     */
    public function setWeightUnit(?string $weightUnit): ProductInterface
    {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    public function getDefaultVariant(): ?ProductVariant
    {
        $variants = $this->getEnabledVariants();
        foreach ($variants as $variant) {
            if ($variant->isDefault()) {
                return $variant;
            }
        }
        return null;
    }

    /**
     * Add category
     *
     * @param TermInterface $category
     *
     * @return Article
     */
    public function addCategory(TermInterface $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param TermInterface $category
     */
    public function removeCategory(TermInterface $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return Collection|TermInterface[]
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Add tag
     *
     * @param TermInterface $tag
     *
     * @return Article
     */
    public function addTag(TermInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param TermInterface $tag
     */
    public function removeTag(TermInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * @return Collection|TermInterface[]
     */
    public function getTags()
    {
        return $this->tags;
    }
}
