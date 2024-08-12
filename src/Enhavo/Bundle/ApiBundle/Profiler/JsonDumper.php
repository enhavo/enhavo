<?php

namespace Enhavo\Bundle\ApiBundle\Profiler;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;

class JsonDumper implements DataDumperInterface
{
    public function dump(Data $data, $output = null): ?string
    {
        $normalizedData = $this->normalizeData($data);
        return json_encode($normalizedData, JSON_PRETTY_PRINT);
    }

    private function normalizeData(Data $data): mixed
    {
        $valueData = $data->getValue();

        if (is_array($valueData)) {
            $normalizedData = [];
            foreach ($valueData as $key => $value) {
                $normalizedData[$key] = $value instanceof Data ? $this->normalizeData($value) : $value;
            }
            return $normalizedData;
        }

        return $valueData;
    }
}
