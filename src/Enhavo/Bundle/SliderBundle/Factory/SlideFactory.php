<?php

namespace Enhavo\Bundle\SliderBundle\Factory;

use Enhavo\Bundle\ResourceBundle\Factory\Factory;
use Enhavo\Bundle\SliderBundle\Model\SlideInterface;
use Enhavo\Bundle\SliderBundle\Model\SliderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class SlideFactory extends Factory
{
    /**
     * @var RepositoryInterface
     */
    private $sliderRepository;

    public function __construct(string $className, RepositoryInterface $sliderRepository)
    {
        parent::__construct($className);
        $this->sliderRepository = $sliderRepository;
    }

    public function createWithSliderId($sliderId = null)
    {
        if ($sliderId === null) {
            return $this->createNew();
        }

        /** @var SliderInterface $slider */
        $slider = $this->sliderRepository->find($sliderId);

        /** @var SlideInterface $slide */
        $slide = $this->createNew();
        $slide->setSlider($slider);
        return $slide;
    }
}
