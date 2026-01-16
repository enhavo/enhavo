<?php

namespace Enhavo\Bundle\TranslationBundle\Client;


use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\TranslationBundle\Translation\TranslationManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlTranslationClient implements TranslationClientInterface
{
    public function __construct(
        private readonly EntityRepository $routeRepository,
        private readonly TranslationManager $translationManager,
        private readonly array $domains,
    ) {
    }

    public function translate(string $text, string $sourceLanguage, string $targetLanguage, array $options = []): ?string
    {
        $options = $this->getOptions($options);

        if ($options['ignore_urls']) {
            return $text;
        }

        $pattern = '~<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>.*?</a>~i';

        return preg_replace_callback($pattern, function ($matches) use ($targetLanguage) {
            $url = $matches[1];

            if (str_starts_with($url, '/') || $this->containsDomain($url)) {
                $path = $this->getPath($url);

                if ($path === null) {
                    return $matches[0];
                }

                $routes = $this->routeRepository->findBy(['staticPrefix' => $path], null, 1);
                if (count($routes) > 0) {
                    $route = $routes[0];
                    $content = $route->getContent();
                    $newPath = $this->translationManager->getProperty($content, 'route', $targetLanguage);
                    if ($newPath != null) {
                        return str_replace($matches[1], $newPath->getStaticPrefix(), $matches[0]);
                    }
                }
            }

            return $matches[0];
        }, $text);
    }

    private function getPath(string $link): ?string
    {
        if (str_starts_with($link, '/')) {
            return $link;
        }

        $pattern = '~https?://[^/]+(/[^"]*)~i';
        preg_match($pattern, $link, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        }
        return null;
    }

    private function containsDomain($url): bool
    {
        foreach ($this->domains as $domain) {
            $host = parse_url($url, PHP_URL_HOST);
            if ($host === $domain) {
                return true;
            }
        }
        return false;
    }

    protected function getOptions($options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setIgnoreUndefined();
        $resolver->setDefaults([
            'ignore_urls' => false
        ]);
        return $resolver->resolve($options);
    }
}
