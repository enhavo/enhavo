<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

interface TranslationClientInterface
{
    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): ?string;
}
