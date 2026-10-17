<?php

use App\Kernel;
use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Formatter\PrettyFormatter;
use Behat\Config\Profile;
use Behat\Config\Suite;
use Enhavo\Bundle\AppBundle\Behat\Context\DebugContext;
use Enhavo\Bundle\AppBundle\Behat\Context\DoctrineContext;
use Enhavo\Bundle\AppBundle\Behat\Context\EmailContext;
use Enhavo\Bundle\AppBundle\Behat\Context\PantherContext;
use Enhavo\Bundle\AppBundle\Behat\Extension\KernelExtension;
use Enhavo\Bundle\AppBundle\Behat\Extension\PantherExtension;
use Enhavo\Bundle\MediaBundle\Behat\Context\MediaContext;
use Enhavo\Bundle\NavigationBundle\Behat\Context\NavigationContext;
use Enhavo\Bundle\SearchBundle\Behat\Context\SearchContext;
use Enhavo\Bundle\UserBundle\Behat\Context\UserContext;

return (new Config())
    ->withProfile((new Profile('default'))
        ->withExtension(new Extension(KernelExtension::class, [
            'class' => Kernel::class,
            'environment' => 'test',
            'debug' => true,
            'dotenv' => dirname(__DIR__, 2).'/.env',
        ]))
        ->withExtension(new Extension(PantherExtension::class, [
            'env' => [
                'PANTHER_APP_ENV' => 'test',
                'PANTHER_WEB_SERVER_DIR' => __DIR__.'/public',
                'PANTHER_CHROME_DRIVER_BINARY' => dirname(__DIR__, 2).'/drivers/chromedriver',
            ],
        ]))
        ->withFormatter(new PrettyFormatter(paths: false))
        ->withSuite((new Suite('web'))
            ->withPaths(__DIR__.'/features')
            ->withContexts(
                PantherContext::class,
                DebugContext::class,
                EmailContext::class,
                DoctrineContext::class,
                MediaContext::class,
                NavigationContext::class,
                SearchContext::class,
                UserContext::class,
            )
        )
    );
