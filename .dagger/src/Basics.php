<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Container;
use Dagger\Directory;

use function Dagger\dag;

#[DaggerObject]
class Basics
{
    #[DaggerFunction]
    public function __construct(
        #[DefaultPath(".")]
        public Directory $source,
    ) {
    }

    #[DaggerFunction]
    #[Doc('Set up env')]
    public function setupPhpEnv(): Container
    {
        return dag()
            ->container()
            ->from('php:8.3-cli-alpine')
            ->withExec(['apk', 'add', '--no-cache', 'libzip-dev', 'zip'])
            ->withExec(['docker-php-ext-install', 'zip', 'exif'])
            ->withFile(
                '/usr/bin/composer',
                dag()->container()->from('composer:2')->file('/usr/bin/composer')
            )
            ->withMountedDirectory('/app', $this->source)
            ->withWorkdir('/app')
            ->withExec(['composer', 'install'])
        ;
    }

    #[DaggerFunction]
    #[Doc('Run phpunit')]
    public function phpunit(): Container
    {
        return $this->setupPhpEnv()
            ->withExec(['bin/phpunit'])
        ;
    }
}
