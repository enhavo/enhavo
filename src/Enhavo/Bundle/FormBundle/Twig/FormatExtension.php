<?php
/**
 * FormatExtension.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Enhavo\Bundle\FormBundle\Formatter\HtmlSanitizer;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    /** @var HtmlSanitizer */
    private $sanitizer;

    /**
     * FormatExtension constructor.
     * @param HtmlSanitizer $sanitizer
     */
    public function __construct(HtmlSanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('currency', array($this, 'formatCurrency'), array('is_safe' => array('html')))
        );
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('currency', array($this, 'formatCurrency'), array('is_safe' => array('html'))),
            new TwigFilter('headline', array($this, 'formatHeadline'), array('is_safe' => array('html'))),
            new TwigFilter('html_sanitize', array($this, 'sanitizeHtml'), array('is_safe' => array('html')))
        );
    }

    public function formatCurrency($value, $currency = 'Euro', $position = 'right')
    {
        $currencyFormatter = $this->container->get(CurrencyFormatter::class);
        return $currencyFormatter->getCurrency($value, $currency, $position);
    }

    public function formatHeadline($value, $class = '', array $attributes = [])
    {
        $attributeParts = [];

        if($class) {
            $attributeParts[] = sprintf('class="%s"',  $class);;
        }

        foreach($attributes as $key => $attrValue) {
            if(is_string($attrValue)) {
                $attributeParts[] = sprintf('%s="%s"', $key, $attrValue);
            } elseif($attrValue) {
                $attributeParts[] = sprintf('%s="%s"', $key, htmlentities(json_encode($attrValue)));
            } else {
                $attributeParts[] = $key;
            }
        }

        $attribute = "";
        if(count($attributeParts)) {
            $attribute = sprintf(' %s', implode(' ', $attributeParts));
        }

        $pattern = '/^<([a-zA-Z0-9-]+)>/';
        if(preg_match($pattern, $value)) {
            $content = preg_replace_callback($pattern, function($matches) use ($attribute) {
                return sprintf('<%s%s>', $matches[1], $attribute);
            }, $value);
        } else {
            $content = sprintf('<div%s>%s</div>', $attribute, $value);
        }

        return $this->sanitizer->sanitize($content);
    }

    public function sanitizeHtml($value, $options = [])
    {
        return $this->sanitizer->sanitize($value, $options);
    }
}
