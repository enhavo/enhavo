<?php

namespace Enhavo\Bundle\SearchBundle\Filter;

class FilterDataBuilder
{
    /** @var FilterData[] */
    private array $filterData = [];

    private array $filterFields = [];

    public function addData(FilterData $data)
    {
        $this->filterData[] = $data;
    }

    public function removeData(FilterData $data)
    {
        if (false !== $key = array_search($data, $this->filterData, true)) {
            array_splice($this->filterData, $key, 1);
        }
    }

    public function getData(): array
    {
        return $this->filterData;
    }

    public function addField(FilterField $field)
    {
        $this->filterFields[] = $field;
    }

    public function removeField(FilterField $field)
    {
        if (false !== $key = array_search($field, $this->filterData, true)) {
            array_splice($this->filterFields, $key, 1);
        }
    }

    public function getFields(): array
    {
        return $this->filterFields;
    }
}
