<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Result;

use Enhavo\Bundle\SearchBundle\Engine\Result\ResultSummary;
use Enhavo\Bundle\SearchBundle\Index\IndexDataProvider;
use Enhavo\Bundle\SearchBundle\Util\Highlighter;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ResultConverter
{
    public function __construct(
        private Highlighter $highlighter,
        private IndexDataProvider $indexDataProvider,
    ) {
    }

    /** @return Result[] */
    public function convert(ResultSummary $summary, $searchTerm, ?ResultConfiguration $configuration = null): array
    {
        if (null === $configuration) {
            $configuration = new ResultConfiguration();
        }

        $data = [];
        foreach ($summary->getEntries() as $entry) {
            $subject = $entry->getSubject();
            $resultData = new Result();

            $text = $this->getText($subject, $configuration);

            $text = $this->highlighter->highlight(
                $text,
                explode(' ', $searchTerm),
                $configuration->getLength(),
                $configuration->getStartTag(),
                $configuration->getCloseTag(),
                $configuration->getConcat()
            );

            $resultData->setText($text);
            $resultData->setTitle($this->guessTitle($subject));
            $resultData->setSubject($subject);

            $data[] = $resultData;
        }

        return $data;
    }

    private function getText($resultItem, ResultConfiguration $configuration)
    {
        $text = $this->indexDataProvider->getRawData($resultItem);
        $text = implode($configuration->getConcat(), $text);

        return $text;
    }

    private function guessTitle($resultItem)
    {
        $properties = ['title', 'name', 'headline', 'header'];
        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($properties as $property) {
            try {
                $value = $accessor->getValue($resultItem, $property);
                if ($value) {
                    return $value;
                }
            } catch (AccessException $e) {
                continue;
            }
        }

        return '';
    }
}
