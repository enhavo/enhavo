<?php

namespace Enhavo\Bundle\UserBundle\DependencyInjection;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Enhavo\Bundle\UserBundle\Configuration\RequestConfigKeyProvider;
use Enhavo\Bundle\UserBundle\Controller\UserController;
use Enhavo\Bundle\UserBundle\Factory\UserFactory;
use Enhavo\Bundle\UserBundle\Form\Type\ChangeEmailConfirmType;
use Enhavo\Bundle\UserBundle\Form\Type\ChangeEmailRequestType;
use Enhavo\Bundle\UserBundle\Form\Type\ChangePasswordType;
use Enhavo\Bundle\UserBundle\Form\Type\DeleteConfirmType;
use Enhavo\Bundle\UserBundle\Form\Type\GroupType;
use Enhavo\Bundle\UserBundle\Form\Type\LoginType;
use Enhavo\Bundle\UserBundle\Form\Type\ProfileType;
use Enhavo\Bundle\UserBundle\Form\Type\RegistrationType;
use Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordRequestType;
use Enhavo\Bundle\UserBundle\Form\Type\ResetPasswordType;
use Enhavo\Bundle\UserBundle\Form\Type\UserType;
use Enhavo\Bundle\UserBundle\Model\Group;
use Enhavo\Bundle\UserBundle\Model\User;
use Enhavo\Bundle\UserBundle\Repository\GroupRepository;
use Enhavo\Bundle\UserBundle\Repository\UserRepository;
use Enhavo\Bundle\UserBundle\User\UserManager;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        $this->addResourceSection($rootNode);
        $this->addParametersSection($rootNode);
        $this->addUserIdentifierSection($rootNode);
        $this->addConfigNode($rootNode);

        return $treeBuilder;
    }

    private function addResourceSection(ArrayNodeDefinition $node)
    {
        $node
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
                                        ->scalarNode('controller')->defaultValue(UserController::class)->end()
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
    }

    private function addParametersSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('default_firewall')->defaultValue('main')->end()
                ->scalarNode('user_manager')->defaultValue(UserManager::class)->end()
                ->scalarNode('config_key_provider')->defaultValue(RequestConfigKeyProvider::class)->end()
            ->end()
        ;
    }

    private function addUserIdentifierSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('user_identifiers')
                    ->prototype('scalar')
                ->end()
            ->end()
        ;
    }

    private function addConfigNode(NodeDefinition $node)
    {
        $prototype = $node
            ->children()
                ->arrayNode('config')
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()

        ;

        $this->addConfigGeneralSection($prototype);
        $this->addConfigRegistrationSection($prototype);
        $this->addConfigProfileSection($prototype);
        $this->addConfigResetPasswordSection($prototype);
        $this->addConfigChangeEmailSection($prototype);
        $this->addConfigLoginSection($prototype);
        $this->addConfigChangePasswordSection($prototype);
        $this->addConfigDeleteSection($prototype);
        $this->addConfigVerificationSection($prototype);
    }

    private function addConfigGeneralSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->variableNode('firewalls')->defaultValue([])->end()
            ->end()
        ;
    }

    private function addConfigRegistrationSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('registration_register')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/registration/register.html.twig')->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->scalarNode('confirmation_route')->isRequired()->cannotBeEmpty()->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('auto_login')->defaultValue(true)->end()
                        ->scalarNode('auto_enabled')->defaultValue(true)->end()
                        ->scalarNode('auto_verified')->defaultValue(false)->end()
                        ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(RegistrationType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('registration_check')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/registration/check.html.twig')->end()
                    ->end()
                ->end()

                ->arrayNode('registration_confirm')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/registration/confirm.html.twig')->end()
                        ->scalarNode('auto_enabled')->defaultValue(true)->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                     ->end()
                ->end()

                ->arrayNode('registration_finish')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/registration/finish.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigProfileSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('profile')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/profile/profile.html.twig')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ProfileType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigResetPasswordSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('reset_password_request')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/reset-password/request.html.twig')->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->scalarNode('confirmation_route')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ResetPasswordRequestType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('reset_password_check')
                    ->children()
                         ->scalarNode('template')->defaultValue('{{ area }}/user/reset-password/check.html.twig')->end()
                    ->end()
                ->end()

                ->arrayNode('reset_password_confirm')
                    ->children()
                        ->scalarNode('auto_login')->defaultValue(true)->end()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/reset-password/confirm.html.twig')->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ResetPasswordType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('reset_password_finish')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/reset-password/finish.html.twig')->end()
                    ->end()
                ->end()
            ;
    }

    private function addConfigChangeEmailSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('change_email_request')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/change-email/request.html.twig')->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->scalarNode('confirmation_route')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ChangeEmailRequestType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('change_email_check')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/change-email/check.html.twig')->end()
                    ->end()
                ->end()

                ->arrayNode('change_email_confirm')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/change-email/confirm.html.twig')->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue(null)->end()
                        ->scalarNode('confirmation_route')->isRequired()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ChangeEmailConfirmType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                        ->scalarNode('auto_login')->defaultValue(true)->end()
                    ->end()
                ->end()

                ->arrayNode('change_email_finish')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/change-email/finish.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigLoginSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('login')
                    ->children()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(LoginType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                        ->scalarNode('repository_method')->defaultValue('loadUserByIdentifier')->cannotBeEmpty()->end()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/login/login.html.twig')->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->scalarNode('route')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('check_route')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('max_failed_login_attempts')->defaultValue(0)->end()
                        ->scalarNode('password_max_age')->defaultValue(null)->end()
                        ->scalarNode('verification_required')->defaultValue(false)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigChangePasswordSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('change_password')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/change-password/change.html.twig')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(ChangePasswordType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigDeleteSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('delete_confirm')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/delete/confirm.html.twig')->end()
                        ->scalarNode('redirect_route')->defaultValue(null)->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('class')->defaultValue(DeleteConfirmType::class)->end()
                                ->variableNode('options')->defaultValue([])->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('delete_finish')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/delete/finish.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigVerificationSection(NodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('verification_request')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/verification/request.html.twig')->end()
                        ->scalarNode('route')->defaultValue(null)->end()
                        ->scalarNode('confirmation_route')->isRequired()->cannotBeEmpty()->end()
                        ->append($this->addConfigMailNode())
                        ->scalarNode('translation_domain')->defaultValue('EnhavoUserBundle')->end()
                    ->end()
                ->end()

                ->arrayNode('verification_confirm')
                    ->children()
                        ->scalarNode('template')->defaultValue('{{ area }}/user/verification/confirm.html.twig')->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    private function addConfigMailNode()
    {
        $treeBuilder = new TreeBuilder('mail');

        return $treeBuilder->getRootNode()
            ->canBeDisabled()
            ->children()
                ->scalarNode('template')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('subject')->defaultValue('registration.mail.subject')->end()
                ->scalarNode('from')->defaultValue(null)->end()
                ->scalarNode('sender_name')->defaultValue(null)->end()
                ->scalarNode('content_type')->defaultValue('text/plain')->end()
            ->end()
        ;
    }
}
