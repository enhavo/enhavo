<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\UserBundle\Tests\Endpoint\Type\ChangePassword;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Tests\Mock\FormFactoryMock;
use Enhavo\Bundle\AppBundle\Tests\Mock\TokenMock;
use Enhavo\Bundle\ResourceBundle\Tests\Mock\ContainerMock;
use Enhavo\Bundle\UserBundle\Configuration\ChangePassword\ChangePasswordConfiguration;
use Enhavo\Bundle\UserBundle\Configuration\ConfigurationProvider;
use Enhavo\Bundle\UserBundle\Endpoint\Type\ChangePassword\ChangePasswordEndpointType;
use Enhavo\Bundle\UserBundle\Tests\Mocks\UserMock;
use Enhavo\Bundle\UserBundle\User\UserManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordEndpointTypeTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new ChangePasswordEndpointTypeDependencies();
        $dependencies->provider = $this->getMockBuilder(ConfigurationProvider::class)->disableOriginalConstructor()->getMock();
        $dependencies->userManager = $this->getMockBuilder(UserManager::class)->disableOriginalConstructor()->getMock();
        $dependencies->translator = $this->getMockBuilder(TranslatorInterface::class)->disableOriginalConstructor()->getMock();
        $dependencies->container = new ContainerMock();
        $dependencies->form = $this->getMockBuilder(FormInterface::class)->getMock();
        $dependencies->formFactory = new FormFactoryMock($dependencies->form);
        $dependencies->tokenStorage = $this->getMockBuilder(TokenStorageInterface::class)->getMock();
        $dependencies->configuration = $this->getMockBuilder(ChangePasswordConfiguration::class)->disableOriginalConstructor()->getMock();
        $dependencies->container->set('security.token_storage', $dependencies->tokenStorage);
        $dependencies->container->set('form.factory', $dependencies->formFactory);
        $dependencies->container->set('serializer', new Serializer());

        return $dependencies;
    }

    public function createInstance(ChangePasswordEndpointTypeDependencies $dependencies)
    {
        $instance = new ChangePasswordEndpointType(
            $dependencies->provider,
            $dependencies->userManager,
            $dependencies->translator,
        );

        $instance->setContainer($dependencies->container);

        return $instance;
    }

    public function testUserForm()
    {
        $dependencies = $this->createDependencies();
        $userMock = new UserMock();
        $dependencies->tokenStorage->method('getToken')->willReturn(new TokenMock($userMock));
        $dependencies->provider->method('getChangePasswordConfiguration')->willReturn($dependencies->configuration);
        $dependencies->configuration->method('getFormClass')->willReturn('any');
        $dependencies->configuration->method('getFormOptions')->willReturn([]);

        $instance = $this->createInstance($dependencies);

        $request = new Request();
        $options = [];
        $data = new Data();
        $context = new Context($request);
        $instance->handleRequest($options, $request, $data, $context);

        $endpointData = $data->normalize();

        $this->assertArrayHasKey('form', $endpointData);
    }

    public function testNoUser()
    {
        $this->expectException(AccessDeniedException::class);

        $dependencies = $this->createDependencies();
        $dependencies->tokenStorage->method('getToken')->willReturn(new TokenMock(null));

        $instance = $this->createInstance($dependencies);

        $request = new Request();
        $options = [];
        $data = new Data();
        $context = new Context($request);
        $instance->handleRequest($options, $request, $data, $context);
    }
}

class ChangePasswordEndpointTypeDependencies
{
    public ConfigurationProvider|MockObject $provider;
    public ChangePasswordConfiguration|MockObject $configuration;
    public UserManager|MockObject $userManager;
    public TranslatorInterface|MockObject $translator;
    public ContainerInterface|MockObject $container;
    public TokenStorageInterface|MockObject $tokenStorage;
    public FormFactoryInterface|MockObject $formFactory;
    public FormInterface|MockObject $form;
}
