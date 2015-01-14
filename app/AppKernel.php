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
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Bazinga\Bundle\JsTranslationBundle\BazingaJsTranslationBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new esperanto\AdminBundle\esperantoAdminBundle(),
            new esperanto\UserBundle\esperantoUserBundle(),
            new esperanto\MediaBundle\esperantoMediaBundle(),
            new esperanto\NewsBundle\esperantoNewsBundle(),
            new esperanto\PageBundle\esperantoPageBundle(),
            new esperanto\CategoryBundle\esperantoCategoryBundle(),
            new esperanto\AssetsBundle\esperantoAssetsBundle(),
            new esperanto\ContentBundle\esperantoContentBundle(),
            new esperanto\ReferenceBundle\esperantoReferenceBundle(),
            new esperanto\SliderBundle\esperantoSliderBundle(),
            new esperanto\SettingBundle\esperantoSettingBundle(),
            new esperanto\SearchBundle\esperantoSearchBundle(),

            //new esperanto\MenuBundle\esperantoMenuBundle(),
            //new esperanto\RecipeBundle\esperantoRecipeBundle(),
            //new esperanto\FrontendTemplateBundle\esperantoFrontendTemplateBundle(),
            //new esperanto\ShopBundle\esperantoShopBundle(),
            //new Sylius\Bundle\MoneyBundle\SyliusMoneyBundle(),
            //new Sylius\Bundle\OrderBundle\SyliusOrderBundle(),
            //new Sylius\Bundle\CartBundle\SyliusCartBundle(),
            //new Sylius\Bundle\ProductBundle\SyliusProductBundle(),
            //new MKS\AdminBundle\MKSAdminBundle(),
            //new MKS\FrontendBundle\MKSFrontendBundle(),
            //new MKS\ShopBundle\MKSShopBundle(),
            //new blutzeMedia\WAFBundle\blutzeMediaWAFBundle(),

            //new HowToVideo\MainBundle\HowToVideoMainBundle(),
            new esperanto\ProjectBundle\esperantoProjectBundle(),
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
