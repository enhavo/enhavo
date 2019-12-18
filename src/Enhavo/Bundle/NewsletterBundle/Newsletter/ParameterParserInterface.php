<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-12-15
 * Time: 22:30
 */

namespace Enhavo\Bundle\NewsletterBundle\Newsletter;


interface ParameterParserInterface
{
    public function parse(string $content, array $parameters): string;
}