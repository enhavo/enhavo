<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-04-14
 * Time: 13:40
 */

namespace Enhavo\Bundle\ResourceBundle\Tests\Mock;


use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatorMock implements TranslatorInterface
{
    public function __construct(
        public string $postFix = ''
    )
    {
    }

    public function trans(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $id . $this->postFix;
    }

    public function setPostFix(string $postFix): void
    {
        $this->postFix = $postFix;
    }

    public function getLocale(): string
    {
        return 'en';
    }
}
