<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            new enhavo\AdminBundle\enhavoAdminBundle(),
            new enhavo\UserBundle\enhavoUserBundle(),
            new enhavo\MediaBundle\enhavoMediaBundle(),
            new enhavo\NewsBundle\enhavoNewsBundle(),
            new enhavo\PageBundle\enhavoPageBundle(),
            new enhavo\CategoryBundle\enhavoCategoryBundle(),
            new enhavo\AssetsBundle\enhavoAssetsBundle(),
            new enhavo\ContentBundle\enhavoContentBundle(),
            new enhavo\ReferenceBundle\enhavoReferenceBundle(),
            new enhavo\SliderBundle\enhavoSliderBundle(),
            new enhavo\SettingBundle\enhavoSettingBundle(),
            new enhavo\SearchBundle\enhavoSearchBundle(),
            new enhavo\DownloadBundle\enhavoDownloadBundle(),

            new enhavo\ProjectBundle\enhavoProjectBundle(),
            new enhavo\NewsletterBundle\enhavoNewsletterBundle(),
            new enhavo\CalendarBundle\enhavoCalendarBundle(),
            new enhavo\ShopBundle\enhavoShopBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
