<?php

namespace Enhavo\Bundle\AppBundle\Util;

class StopWatchResult
{
    private float $time;
    private int $memory;

    public function __construct(float $time, int $memory)
    {
        $this->time = $time;
        $this->memory = $memory;
    }

    /**
     * @return float|null
     */
    public function getTime(): ?float
    {
        return $this->time;
    }

    /**
     * @return string|null
     */
    public function getTimeReadable(): ?string
    {
        $timeMinutes = (int)floor($this->time / 60);
        $timeSeconds = (int)($this->time % 60);
        if ($timeMinutes > 0) {
            return sprintf('%sm%ss', $timeMinutes, $timeSeconds);
        }
        return $timeSeconds . 's';
    }

    /**
     * @return int|null
     */
    public function getMemory(): ?int
    {
        return $this->memory;
    }

    /**
     * @return string|null
     */
    public function getMemoryReadable(): ?string
    {
        if ($this->memory < 1024) {
            return $this->memory . 'b';
        } elseif ($this->memory < 1048576) {
            return round($this->memory / 1024) . 'kb';
        } else {
            return round($this->memory / 1048576) . 'mb';
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('time %s, memory %s', $this->getTimeReadable(), $this->getMemoryReadable());
    }
}
