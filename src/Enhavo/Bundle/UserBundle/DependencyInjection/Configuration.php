<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\UserBundle\Factory\UserFactory;
use Enhavo\Bundle\UserBundle\Form\Type\ChangePasswordFormType;
use Enhavo\Bundle\UserBundle\Form\Type\GroupType;
use Enhavo\Bundle\UserBundle\Form\Type\ProfileType;
use Enhavo\Bundle\UserBundle\Form\Type\RegistrationType;
use Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordRequestType;
use Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordType;
use Enhavo\Bundle\UserBundle\Form\Type\UserType;
use Enhavo\Bundle\UserBundle\Model\Group;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\GroupRepository;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('enhavo_user');
        $rootNode = $treeBuilder->getRootNode();

        $this->addConfigNode($rootNode);

        $rootNode
            // Driver used by the resource bundle
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()

            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('user')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(User::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(UserRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(UserFactory::class)->end()
                                        ->scalarNode('form')->defaultValue(UserType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('group')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Group::class)->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->end()
                                        ->scalarNode('repository')->defaultValue(GroupRepository::class)->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->scalarNode('form')->defaultValue(GroupType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function addConfigNode(NodeDefinition $rootNode): NodeDefinition
    {
        $subNode = $rootNode->children();

        $parametersNode = $subNode->arrayNode('parameters')->addDefaultsIfNotSet()->children();
        $parametersNode
            ->variableNode('default_firewall')->defaultValue('main')->end()
        ;
        $parametersNode->end();

        $mailNode = $subNode->arrayNode('mail')->addDefaultsIfNotSet()->children();
        $mailNode
            ->variableNode('from')->end()
            ->variableNode('sender_name')->end()
        ;
        $mailNode->end();

        $mapperNode = $subNode->arrayNode('mapper')->addDefaultsIfNotSet()->children();
        $mapperNode
            ->variableNode('credential_properties')->defaultValue(['email'])->end()
            ->variableNode('register_properties')->defaultValue(['email'])->end()
            ->scalarNode('glue')->defaultValue('.')->end()
        ;

        $mapperNode->end();

        $configNode = $subNode->arrayNode('config')->ignoreExtraKeys()->addDefaultsIfNotSet()->children();
        $themeNode = $configNode->arrayNode('theme')->ignoreExtraKeys()->addDefaultsIfNotSet()->children();
        $themeNode->arrayNode('registration_register')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/registration/register.html.twig')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_user_theme_registration_check')->end()
            ->scalarNode('confirmation_route')->defaultValue('enhavo_user_theme_registration_confirm')->end()
            ->scalarNode('mail_template')->defaultValue('mail/security/registration.html.twig')->end()
            ->scalarNode('mail_subject')->defaultValue('registration.mail.subject')->end()
            ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(RegistrationType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
        ->end();

        $themeNode->arrayNode('registration_check')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/registration/check.html.twig')->end()
        ->end();

        $themeNode->arrayNode('registration_finish')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/registration/finish.html.twig')->end()
        ->end();

        $themeNode->arrayNode('registration_confirm')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/registration/confirm.html.twig')->end()
            ->scalarNode('mail_template')->defaultValue('mail/security/confirmation.html.twig')->end()
            ->scalarNode('mail_subject')->defaultValue('confirmation.mail.subject')->end()
            ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_user_theme_registration_finish')->end()
        ->end();

        $themeNode->arrayNode('profile')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/resource/user/profile.html.twig')->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ProfileType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
        ->end();

        $themeNode->arrayNode('reset_password_request')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/reset-password/request.html.twig')->end()
            ->scalarNode('mail_template')->defaultValue('mail/security/reset-password.html.twig')->end()
            ->scalarNode('mail_subject')->defaultValue('reset.mail.subject')->end()
            ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_user_theme_reset_password_check')->end()
            ->scalarNode('confirmation_route')->defaultValue('enhavo_user_theme_reset_password_confirm')->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ResetPasswordRequestType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
        ->end();

        $themeNode->arrayNode('reset_password_check')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/reset-password/check.html.twig')->end()
        ->end();

        $themeNode->arrayNode('reset_password_confirm')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/reset-password/confirm.html.twig')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_user_theme_reset_password_finish')->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ResetPasswordType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
        ->end();

        $themeNode->arrayNode('reset_password_finish')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/reset-password/finish.html.twig')->end()
        ->end();

        $themeNode->arrayNode('login')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('theme/security/login.html.twig')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_user_theme_user_profile')->end()
            ->scalarNode('route')->defaultValue('enhavo_user_theme_security_login')->cannotBeEmpty()->end()
        ->end();

        $themeNode->end();

        $adminNode = $configNode->arrayNode('admin')->ignoreExtraKeys()->addDefaultsIfNotSet()->children();

        $adminNode->arrayNode('reset_password_request')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('admin/security/reset-password/request.html.twig')->end()
            ->scalarNode('mail_template')->defaultValue('mail/security/reset-password.html.twig')->end()
            ->scalarNode('mail_subject')->defaultValue('reset.mail.subject')->end()
            ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
            ->scalarNode('confirmation_route')->defaultValue('enhavo_user_reset_password_confirm')->end()
            ->scalarNode('stylesheets')->defaultValue(['enhavo/user/login'])->end()
            ->scalarNode('javascripts')->defaultValue(['enhavo/user/login'])->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ResetPasswordRequestType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
            ->end();

        $adminNode->arrayNode('reset_password_confirm')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('admin/security/reset-password/confirm.html.twig')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_app_index')->end()
            ->scalarNode('stylesheets')->defaultValue(['enhavo/user/login'])->end()
            ->scalarNode('javascripts')->defaultValue(['enhavo/user/login'])->end()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ResetPasswordType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
            ->end();

        $adminNode->arrayNode('login')->addDefaultsIfNotSet()->children()
            ->scalarNode('template')->defaultValue('admin/security/login/login.html.twig')->end()
            ->scalarNode('redirect_route')->defaultValue('enhavo_app_index')->end()
            ->scalarNode('route')->defaultValue('enhavo_user_security_login')->cannotBeEmpty()->end()
            ->scalarNode('stylesheets')->defaultValue(['enhavo/user/login'])->end()
            ->scalarNode('javascripts')->defaultValue(['enhavo/user/login'])->end()
        ->end();

        $adminNode->arrayNode('change_password')->addDefaultsIfNotSet()->children()
            ->arrayNode('form')->addDefaultsIfNotSet()->children()
                ->scalarNode('class')->defaultValue(ChangePasswordFormType::class)->end()
                ->scalarNode('options')->defaultValue([])->end()
            ->end()
        ->end();

        $adminNode->end();

        $configNode->end();
        $subNode->end();
        return $rootNode;
    }
}
