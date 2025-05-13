<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\FormBundle\Formatter\HtmlSanitizer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @author gseidel
 */
class FormatExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /**
     * FormatExtension constructor.
     */
    public function __construct(
        private readonly HtmlSanitizer $sanitizer,
        private readonly array $htmlSanitizerConfig,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('currency', [$this, 'formatCurrency'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('currency', [$this, 'formatCurrency'], ['is_safe' => ['html']]),
            new TwigFilter('headline', [$this, 'formatHeadline'], ['is_safe' => ['html']]),
            new TwigFilter('html_sanitize', [$this, 'sanitizeHtml'], ['is_safe' => ['html']]),
        ];
    }

    public function formatCurrency($value, $currency = 'Euro', $position = 'right')
    {
        $currencyFormatter = $this->container->get(CurrencyFormatter::class);

        return $currencyFormatter->getCurrency($value, $currency, $position);
    }

    public function formatHeadline($value, $class = '', array $attributes = [])
    {
        $attributeParts = [];

        if ($class) {
            $attributeParts[] = sprintf('class="%s"', $class);
        }

        foreach ($attributes as $key => $attrValue) {
            if (is_string($attrValue)) {
                $attributeParts[] = sprintf('%s="%s"', $key, $attrValue);
            } elseif ($attrValue) {
                $attributeParts[] = sprintf('%s="%s"', $key, htmlentities(json_encode($attrValue)));
            } else {
                $attributeParts[] = $key;
            }
        }

        $attribute = '';
        if (count($attributeParts)) {
            $attribute = sprintf(' %s', implode(' ', $attributeParts));
        }

        $pattern = '/^<([a-zA-Z0-9-]+)>/';
        if (preg_match($pattern, $value)) {
            $content = preg_replace_callback($pattern, function ($matches) use ($attribute) {
                return sprintf('<%s%s>', $matches[1], $attribute);
            }, $value);
        } else {
            $content = sprintf('<div%s>%s</div>', $attribute, $value);
        }

        return $this->sanitizer->sanitize($content);
    }

    public function sanitizeHtml($value, $options = [])
    {
        return $this->sanitizer->sanitize($value, array_merge($this->htmlSanitizerConfig, $options));
    }
}
