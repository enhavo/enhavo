<?php

namespace Enhavo\Bundle\ResourceBundle\Tab\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Bundle\ResourceBundle\Tab\AbstractTabType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormTabType extends AbstractTabType
{
    public function createViewData(array $options, Data $data, InputInterface $input = null): void
    {
        $data->set('arrangement', $this->generateArrangement($options['arrangement']));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'component' => 'tab-form',
            'arrangement' => null,
            'model' => 'FormTab',
        ]);
    }

    public static function getName(): ?string
    {
        return 'form';
    }

    private function generateArrangement($arrangement): array
    {
        $lines = is_array($arrangement) ? $arrangement : explode("\n", $arrangement);

        $rows = [];
        foreach ($lines as $line) {
            $rows[] = $this->generateRow($line);
        }
        return $rows;
    }

    private function generateRow($line): array
    {
        $columns = explode("|", $line);
        $data = [];
        foreach ($columns as $column) {
            preg_match('/([A-Za-z.-_]+)/', trim($column), $matches);
            $key = $matches[1] ?? null;

            preg_match('/\{([0-9]+)\}/', trim($column), $matches);
            $size = intval($matches[1] ?? 1);

            if ($key !== null && $size > 0) {
                $data[] = [
                    'key' => $key,
                    'size' => $size,
                ];
            }
        }
        return $data;
    }
}
