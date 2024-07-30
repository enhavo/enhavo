<?php

namespace Enhavo\Bundle\ResourceBundle\Batch\Type;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Batch\AbstractBatchType;
use Enhavo\Bundle\ResourceBundle\RouteResolver\RouteResolverInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseBatchType extends AbstractBatchType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RouteResolverInterface $routeResolver,
        private readonly RouterInterface $router,
    )
    {
    }

    public function createViewData(array $options, Data $data): void
    {
        $data->set('label', $this->getLabel($options));
        $data->set('confirmMessage', $this->getConfirmMessage($options));
        $data->set('position', $options['position']);
        $data->set('component', $options['component']);
        $data->set('model', $options['model']);
        $data->set('url', $options['route'] ? $this->router->generate($options['route'], $options['route_parameters']) : null);
    }

    private function getLabel($options): string
    {
        return $this->translator->trans($options['label'], [], $options['translation_domain']);
    }

    private function getConfirmMessage($options): ?string
    {
        if($options['confirm_message'] !== null) {
            return $this->translator->trans($options['confirm_message'], [], $options['translation_domain']);
        }
        return null;
    }

    public function getPermission(array $options, EntityRepository $repository): mixed
    {
        return $options['permission'];
    }

    public function isEnabled(array $options): bool
    {
        return $options['enabled'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'permission'  => null,
            'position'  => 0,
            'translation_domain' => null,
            'enabled' => true,
            'confirm_message' => null,
            'component' => 'batch-url',
            'model' => 'UrlBatch',
            'route' => $this->routeResolver->getRoute('batch'),
            'route_parameters' => [],
        ]);

        $resolver->setRequired(['label']);
    }

    public static function getParentType(): ?string
    {
        return null;
    }
}
