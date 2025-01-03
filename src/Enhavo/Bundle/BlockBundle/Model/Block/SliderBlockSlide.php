<?php

namespace Enhavo\Bundle\BlockBundle\Model\Block;

use Enhavo\Bundle\MediaBundle\Model\FileInterface;

class SliderBlockSlide
{
    private ?int $id;

    private ?string $title;

    private ?string $text;

    private ?string $url;

    private ?int $position;

    private ?FileInterface $image;

    private ?SliderBlock $slider;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): void
    {
        $this->text = $text;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    public function getImage(): ?FileInterface
    {
        return $this->image;
    }

    public function setImage(?FileInterface $image): void
    {
        $this->image = $image;
    }

    public function getSlider(): ?SliderBlock
    {
        return $this->slider;
    }

    public function setSlider(?SliderBlock $slider): void
    {
        $this->slider = $slider;
    }
}
