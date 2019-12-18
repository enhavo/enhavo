<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-12-15
 * Time: 22:29
 */

namespace Enhavo\Bundle\NewsletterBundle\Newsletter;


use Enhavo\Bundle\AppBundle\Util\NameTransformer;

class ParameterParser implements ParameterParserInterface
{
    public function parse(string $content, array $parameters): string
    {
        $nameTransformer = new NameTransformer();
        foreach($parameters as $key => $value) {
            $var = sprintf('${%s}', strtoupper($nameTransformer->snakeCase($key)));
            $content = str_replace($var, $value, $content);
        }
        return $content;
    }
}