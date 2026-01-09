<?php

declare(strict_types=1);

namespace DaggerModule;

use Dagger\Attribute\DaggerFunction;
use Dagger\Attribute\DaggerObject;
use Dagger\Attribute\DefaultPath;
use Dagger\Attribute\Doc;
use Dagger\Container;
use Dagger\Directory;
use Dagger\Secret;

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
    public function phpunit(string $args = ''): Container
    {
        return $this->setupPhpEnv()
            ->withExec(['bin/phpunit', $args])
        ;
    }

    #[DaggerFunction]
    #[Doc('Push subtrees')]
    public function pushSubtrees(string $branch): Container
    {
        return dag()
            ->container()
            ->from('php:8.3-cli-alpine')
            ->withMountedDirectory('/app', $this->source)
            ->withWorkdir('/app')
            ->withExec(['curl', '-sLO', 'https://github.com/enhavo/enhavo-cli/releases/latest/download/enhavo.phar'])
            ->withExec(['chmod', '+x', 'enhavo.phar'])
            ->withExec(['apk', 'add', '--no-cache', 'git'])
            ->withExec(['git', 'config', '--global', 'user.email', '"bot@enhavo.com"'])
            ->withExec(['git', 'config', '--global', 'user.name', '"enhavo bot"'])
            ->withExec(['enhavo.phar', 'push-subtree', '--yes', '-vvv', '--branch', escapeshellarg($branch)])
            ->terminal()
        ;
    }

    #[DaggerFunction]
    #[Doc('npm release')]
    public function npmRelease(string $version, Secret $token): Container
    {
        return dag()
            ->container()
            ->from('node:24-alpine')
            ->withMountedDirectory('/app', $this->source)
            ->withWorkdir('/app')
            ->withSecretVariable('NPM_TOKEN', $token)
            ->withExec(['apk', 'add', 'git', 'jq'])

            ->withExec(['yarn', 'config', 'set', 'npmRegistryServer', 'https://registry.npmjs.org'])
            ->withExec(['yarn', 'config', 'set', 'npmAlwaysAuth', 'true'])
            ->withExec(['sh', '-c', 'yarn config set npmAuthToken $NPM_TOKEN'])

            // Find all package.json files (excluding node_modules) and set .version everywhere
            ->withExec([
                'sh',
                '-c',
                'find /app/packages -name package.json -not -path \'*/node_modules/*\' -print0 | while IFS= read -r -d \'\' f; do jq --arg v '.escapeshellarg($version).' \'.version=$v\' "$f" > "$f.tmp" && mv "$f.tmp" "$f"; done'
            ])
            ->withExec(['yarn', 'workspaces', 'foreach', '-A', 'npm', 'publish', '--access', 'public'])
        ;
    }
}
