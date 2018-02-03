<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new Sylius\Bundle\CartBundle\SyliusCartBundle(),
            new Sylius\Bundle\OrderBundle\SyliusOrderBundle(),
            new Sylius\Bundle\MoneyBundle\SyliusMoneyBundle(),
            new Sylius\Bundle\CurrencyBundle\SyliusCurrencyBundle(),
            new Sylius\Bundle\AddressingBundle\SyliusAddressingBundle(),
            new Sylius\Bundle\PromotionBundle\SyliusPromotionBundle(),
            new Sylius\Bundle\PaymentBundle\SyliusPaymentBundle(),
            new Sylius\Bundle\PayumBundle\SyliusPayumBundle(),
            new Sylius\Bundle\ShippingBundle\SyliusShippingBundle(),
            new Sylius\Bundle\TaxationBundle\SyliusTaxationBundle(),
            new Sylius\Bundle\ProductBundle\SyliusProductBundle(),
            new Sylius\Bundle\AttributeBundle\SyliusAttributeBundle(),
            new Sylius\Bundle\ArchetypeBundle\SyliusArchetypeBundle(),
            new Sylius\Bundle\VariationBundle\SyliusVariationBundle(),
            new Sylius\Bundle\InventoryBundle\SyliusInventoryBundle(),

            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new FOS\RestBundle\FOSRestBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
            new Payum\Bundle\PayumBundle\PayumBundle(),

            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),

            new Enhavo\Bundle\AppBundle\EnhavoAppBundle(),
            new Enhavo\Bundle\UserBundle\EnhavoUserBundle(),
            new Enhavo\Bundle\MediaBundle\EnhavoMediaBundle(),
            new Enhavo\Bundle\ArticleBundle\EnhavoArticleBundle(),
            new Enhavo\Bundle\PageBundle\EnhavoPageBundle(),
            new Enhavo\Bundle\CategoryBundle\EnhavoCategoryBundle(),
            new Enhavo\Bundle\AssetsBundle\EnhavoAssetsBundle(),
            new Enhavo\Bundle\GridBundle\EnhavoGridBundle(),
            new Enhavo\Bundle\SliderBundle\EnhavoSliderBundle(),
            new Enhavo\Bundle\SettingBundle\EnhavoSettingBundle($this),
            new Enhavo\Bundle\SearchBundle\EnhavoSearchBundle($this),
            new Enhavo\Bundle\SerializerBundle\EnhavoSerializerBundle(),
            new Enhavo\Bundle\DownloadBundle\EnhavoDownloadBundle(),
            new Enhavo\Bundle\NewsletterBundle\EnhavoNewsletterBundle(),
            new Enhavo\Bundle\CalendarBundle\EnhavoCalendarBundle(),
            new Enhavo\Bundle\ShopBundle\EnhavoShopBundle(),
            new Enhavo\Bundle\ContentBundle\EnhavoContentBundle(),
            new Enhavo\Bundle\DashboardBundle\EnhavoDashboardBundle(),
            new Enhavo\Bundle\ContactBundle\EnhavoContactBundle(),
            new Enhavo\Bundle\MigrationBundle\EnhavoMigrationBundle(),
            new Enhavo\Bundle\InstallerBundle\EnhavoInstallerBundle(),
            new Enhavo\Bundle\ThemeBundle\EnhavoThemeBundle(),
            new Enhavo\Bundle\GeneratorBundle\EnhavoGeneratorBundle(),
            new Enhavo\Bundle\TranslationBundle\EnhavoTranslationBundle($this),
            new Enhavo\Bundle\CommentBundle\EnhavoCommentBundle(),
            new Enhavo\Bundle\NavigationBundle\EnhavoNavigationBundle(),
            new Enhavo\Bundle\ProjectBundle\EnhavoProjectBundle(),
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
