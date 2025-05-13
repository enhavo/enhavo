<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enhavo\Bundle\BlockBundle\Entity\AbstractBlock;

class SliderBlock extends AbstractBlock
{
    /** @var Collection<SliderBlockSlide> */
    private Collection $slides;

    public function __construct()
    {
        $this->slides = new ArrayCollection();
    }

    public function getSlides(): Collection
    {
        return $this->slides;
    }

    public function addSlide(SliderBlockSlide $slide): void
    {
        $this->slides->add($slide);
        $slide->setSlider($this);
    }

    public function removeSlide(SliderBlockSlide $slide): void
    {
        $this->slides->removeElement($slide);
        $slide->setSlider(null);
    }
}
