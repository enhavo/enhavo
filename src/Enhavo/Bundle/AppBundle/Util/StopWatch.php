<?php

namespace Enhavo\Bundle\AppBundle\Util;

class StopWatch
{
    private ?float $startTime = null;
    private ?int $memoryUsageBefore = null;

    private ?StopWatchResult $result = null;

    public function start(): void
    {
        $this->startTime = microtime(true);
        $this->memoryUsageBefore = memory_get_usage();
    }

    public function stop(): StopWatch
    {
        $endTime = microtime(true);
        $memoryUsageAfter = memory_get_usage();

        $time = $endTime - $this->startTime;

        $memoryUsage = ($memoryUsageAfter - $this->memoryUsageBefore);

        $this->result = new StopWatchResult($time, $memoryUsage);

        return $this;
   }

   public function getResult(): ?StopWatchResult
   {
       return $this->result;
   }
}
