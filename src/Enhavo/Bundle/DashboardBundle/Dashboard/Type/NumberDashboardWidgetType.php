<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\DashboardBundle\Dashboard\Type;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\DashboardBundle\Dashboard\AbstractDashboardWidgetType;
use Enhavo\Bundle\ResourceBundle\Resource\ResourceManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NumberDashboardWidgetType extends AbstractDashboardWidgetType
{
    private ?ContainerInterface $container = null;

    public function __construct(
        private TranslatorInterface $translator,
        private ResourceManager $resourceManager,
    ) {
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    public function createViewData(array $options, Data $data): void
    {
        $value = call_user_func_array([$this->getRepository($options['repository']), $options['repository_method']], $options['repository_arguments']);

        if (is_array($value) || $value instanceof \Countable) {
            $value = count($value);
        }

        $data->set('value', $value);
        $data->set('label', $this->translator->trans($options['label'], [], $options['translation_domain']));
    }

    private function getRepository(string $repositoryName): object
    {
        if ($this->container->has($repositoryName)) {
            return $this->container->get($repositoryName);
        }

        $repository = $this->resourceManager->getRepository($repositoryName);
        if ($repository) {
            return $repository;
        }

        throw new \Exception(sprintf('NumberDashboardWidgetType: Can\' find repository with value "%s"', $repositoryName));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'component' => 'dashboard-widget-number',
            'translation_domain' => null,
            'repository_arguments' => [],
        ]);

        $resolver->setRequired([
            'label',
            'repository',
            'repository_method',
        ]);
    }

    public static function getName(): ?string
    {
        return 'number';
    }
}
