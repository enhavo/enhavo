<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

class ChainTranslationClient implements TranslationClientInterface
{
    /** @var TranslationClientInterface[] */
    private array $clients = [];

    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): ?string
    {
        if (count($this->clients) === 0) {
            return null;
        }

        foreach ($this->clients as $client) {
            $text = $client->translate($text, $sourceLanguage, $targetLanguage, $options);
            if ($text === null) {
                return null;
            }
        }

        return $text;
    }

    public function addClient(TranslationClientInterface $client): void
    {
        $this->clients[] = $client;
    }
}
