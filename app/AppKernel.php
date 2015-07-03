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

            new Enhavo\Bundle\AppBundle\EnhavoAppBundle(),
            new Enhavo\Bundle\UserBundle\enhavoUserBundle(),
            new Enhavo\Bundle\MediaBundle\enhavoMediaBundle(),
            new Enhavo\Bundle\ArticleBundle\EnhavoArticleBundle(),
            new Enhavo\Bundle\PageBundle\enhavoPageBundle(),
            new Enhavo\Bundle\CategoryBundle\enhavoCategoryBundle(),
            new Enhavo\Bundle\AssetsBundle\enhavoAssetsBundle(),
            new Enhavo\Bundle\ContentGridBundle\EnhavoContentGridBundle(),
            new Enhavo\Bundle\ReferenceBundle\enhavoReferenceBundle(),
            new Enhavo\Bundle\SliderBundle\enhavoSliderBundle(),
            new Enhavo\Bundle\SettingBundle\enhavoSettingBundle(),
            new Enhavo\Bundle\SearchBundle\enhavoSearchBundle(),
            new Enhavo\Bundle\DownloadBundle\enhavoDownloadBundle(),

            new Enhavo\Bundle\ProjectBundle\enhavoProjectBundle(),
            new Enhavo\Bundle\NewsletterBundle\enhavoNewsletterBundle(),
            new Enhavo\Bundle\CalendarBundle\enhavoCalendarBundle(),
            new Enhavo\Bundle\ShopBundle\enhavoShopBundle(),
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
