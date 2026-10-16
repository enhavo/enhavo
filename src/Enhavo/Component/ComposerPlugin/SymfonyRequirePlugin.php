<?php

namespace Enhavo\Component\ComposerPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\BasePackage;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PrePoolCreateEvent;
use Composer\Semver\Constraint\Constraint;
use Composer\Semver\Constraint\ConstraintInterface;
use Composer\Semver\VersionParser;

/**
 * Replacement for the symfony/flex "extra.symfony.require" feature.
 *
 * Removes all versions of symfony/* packages from the pool that don't match the constraint.
 * Packages without any matching version (e.g. polyfills, contracts or bundles with their own
 * release cycle) are left untouched.
 */
class SymfonyRequirePlugin implements PluginInterface, EventSubscriberInterface
{
    private ?ConstraintInterface $constraint = null;
    private ?IOInterface $io = null;

    public function activate(Composer $composer, IOInterface $io): void
    {
        $this->io = $io;

        $require = getenv('SYMFONY_REQUIRE') ?: ($composer->getPackage()->getExtra()['symfony']['require'] ?? null);
        if ($require) {
            $this->constraint = (new VersionParser())->parseConstraints($require);
            $io->writeError(sprintf('<info>Restricting symfony/* packages to "%s"</info>', $require));
        }
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PluginEvents::PRE_POOL_CREATE => 'filterPackages',
        ];
    }

    public function filterPackages(PrePoolCreateEvent $event): void
    {
        if (null === $this->constraint) {
            return;
        }

        $packages = $event->getPackages();

        $matchingNames = [];
        foreach ($packages as $package) {
            if ($this->isSymfonyPackage($package) && $this->matches($package)) {
                $matchingNames[$package->getName()] = true;
            }
        }

        $filtered = [];
        foreach ($packages as $package) {
            if (isset($matchingNames[$package->getName()]) && !$this->matches($package)) {
                $this->io?->writeError(sprintf('Filtered out %s %s', $package->getName(), $package->getPrettyVersion()), true, IOInterface::DEBUG);
                continue;
            }
            $filtered[] = $package;
        }

        $event->setPackages($filtered);
    }

    private function isSymfonyPackage(BasePackage $package): bool
    {
        return str_starts_with($package->getName(), 'symfony/');
    }

    private function matches(BasePackage $package): bool
    {
        return $this->constraint->matches(new Constraint('==', $package->getVersion()));
    }
}
