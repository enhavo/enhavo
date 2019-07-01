<?php
/**
 * FormatExtension.php
 *
 * @since 04/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\FormBundle\Twig;

use Enhavo\Bundle\FormBundle\Formatter\CurrencyFormatter;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    use ContainerAwareTrait;

    public function getFunctions()
    {
        return array(
            new TwigFunction('currency', array($this, 'getCurrency'), array('is_safe' => array('html')))
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
            return preg_replace_callback($pattern, function($matches) use ($attribute) {
                return sprintf('<%s%s>', $matches[1], $attribute);
            }, $value);
        } else {
            return sprintf('<div%s>%s</div>', $attribute, $value);
        }
    }

    public function sanitizeHtml($value, $tags = ['script', 'style', 'iframe', 'link'])
    {
        $dom = new \DOMDocument();
        $dom->loadHTML($value);
        $tagsToRemove = $tags;
        foreach($tagsToRemove as $tag) {
            $element = $dom->getElementsByTagName($tag);
            foreach($element  as $item){
                $item->parentNode->removeChild($item);
            }
        }
        return $dom->saveHTML();
    }


}
