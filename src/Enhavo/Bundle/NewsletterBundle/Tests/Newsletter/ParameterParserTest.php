<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NewsletterBundle\Tests\Newsletter;

use Enhavo\Bundle\NewsletterBundle\Newsletter\ParameterParser;
use PHPUnit\Framework\TestCase;

class ParameterParserTest extends TestCase
{
    public function testParse()
    {
        $parameterParser = new ParameterParser();
        $content = 'I say ${PARAM_ONE} ${PARAM_TWO}!';
        $content = $parameterParser->parse($content, [
            'paramOne' => 'Hello',
            'param_two' => 'World',
        ]);
        $this->assertEquals('I say Hello World!', $content);
    }

    public function testParseRecursive()
    {
        $parameterParser = new ParameterParser();
        $content = 'Foo${LEVEL_ONE.LEVEL_TWO} ${LEVEL_ONE.MORE.LEVEL3}';
        $content = $parameterParser->parse($content, [
            'levelOne' => [
                'levelTwo' => 'bar',
                'more' => [
                    'level3' => '!',
                ],
            ],
        ]);
        $this->assertEquals('Foobar !', $content);
    }

    public function testVariableNotExists()
    {
        $parameterParser = new ParameterParser();
        $content = 'Hello ${I_DONT_KNOW_THIS}';
        $content = $parameterParser->parse($content, []);
        $this->assertEquals('Hello ', $content);
    }
}
