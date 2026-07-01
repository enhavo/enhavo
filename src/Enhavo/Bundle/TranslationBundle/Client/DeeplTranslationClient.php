<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DeeplTranslationClient implements TranslationClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private readonly ContextNormalizer $contextNormalizer,
        private readonly ContextProviderInterface $contextProvider,
        private readonly ?string $apiKey,
        private readonly ?string $glossaryId = null,
    )
    {
    }

    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): ?string
    {
        $options = $this->getOptions($options);

        $parameters = [
            'text' => $text,
            'target_lang' => $targetLanguage,
            'source_lang' => $sourceLanguage,
        ];

        if ($options['glossary_id']) {
            $parameters['glossary_id'] = $options['glossary_id'];
        }

        if ($options['html']) {
            $parameters['tag_handling'] = 'html';
        }

        if ($options['context'] || $this->contextProvider->getText()) {
            $parameters['context'] = $this->contextProvider->getText() . '.' . $this->contextNormalizer->getText($options['context'], $options['context_groups']);
        }

        $response = $this->client->request('POST', 'https://api.deepl.com/v2/translate', [
            'headers' => [
                'Authorization' => 'DeepL-Auth-Key ' . $this->apiKey,
            ],
            'body' => $parameters,
            'timeout' => 3.5
        ]);

        $value = $response->toArray()['translations'][0]['text'];

        return $value;
    }

    protected function getOptions($options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setIgnoreUndefined();
        $resolver->setDefaults([
            'glossary_id' => $this->glossaryId,
            'html' => false,
            'context' => null,
            'context_groups' => [],
        ]);
        return $resolver->resolve($options);
    }
}
