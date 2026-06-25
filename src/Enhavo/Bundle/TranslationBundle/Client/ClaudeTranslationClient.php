<?php

namespace Enhavo\Bundle\TranslationBundle\Client;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClaudeTranslationClient implements TranslationClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private readonly ContextHelper $contextHelper,
        private readonly ?string $apiKey,
        private readonly ?string $version = null,
        private readonly ?string $context = null,
    )
    {
    }

    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): ?string
    {
        $options = $this->getOptions($options);

        $systemPrompt = [];

        $mainPrompt = sprintf(
            'You are a professional translator. Translate the given text from %s to %s. Return only the translated text without any explanation or additional content.',
            $sourceLanguage,
            $targetLanguage,
        );

        if ($options['html']) {
            $mainPrompt .= ' The text contains HTML markup. Preserve all HTML tags exactly as they are and only translate the text content.';
        }

        if ($options['context'] || $this->context) {
            $mainPrompt .= 'Use the context for terminology and tone. Output only the translation.';
        }

        $systemPrompt[] = [
            'type' => 'text',
            'text' => $mainPrompt,
        ];

        if ($options['context'] || $this->context) {
            $contextParts = array_filter([$this->context, $this->contextHelper->getText($options['context'], $options['context_groups'])]);
            $contextPrompt = sprintf(' Context: %s.', implode('. ', $contextParts));
            $systemPrompt[] = [
                'type' => 'text',
                'text' => $contextPrompt,
                'cache_control' => ['type' => 'ephemeral'],
            ];
        }

        $response = $this->client->request('POST', 'https://api.anthropic.com/v1/messages', [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => $this->version ?? '2023-06-01',
            ],
            'json' => [
                'model' => 'claude-haiku-4-5-20251001',
                'max_tokens' => 4096,
                'system' => $systemPrompt,
                'messages' => [
                    ['role' => 'user', 'content' => $text],
                ],
            ],
            'timeout' => 30,
        ]);

        $data = $response->toArray();

        return $data['content'][0]['text'] ?? null;
    }

    protected function getOptions($options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setIgnoreUndefined();
        $resolver->setDefaults([
            'html' => false,
            'context' => null,
            'context_groups' => [],
        ]);
        return $resolver->resolve($options);
    }
}
