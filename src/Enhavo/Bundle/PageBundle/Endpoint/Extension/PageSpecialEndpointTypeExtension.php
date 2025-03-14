<?php

namespace Enhavo\Bundle\PageBundle\Endpoint\Extension;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\AbstractEndpointTypeExtension;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\AppBundle\Endpoint\Type\AreaEndpointType;
use Enhavo\Bundle\AppBundle\Endpoint\Type\ViewEndpointType;
use Enhavo\Bundle\PageBundle\Model\PageInterface;
use Enhavo\Bundle\PageBundle\Repository\PageRepository;
use Enhavo\Bundle\RoutingBundle\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageSpecialEndpointTypeExtension extends AbstractEndpointTypeExtension
{
    public function __construct(
        private readonly array               $specials,
        private readonly Router              $router,
        private readonly PageRepository      $pageRepository,
        private readonly TranslatorInterface $translator,

    )
    {
    }

    public function handleRequest($options, Request $request, Data $data, Context $context)
    {
        if (!$options['page_specials']) {
            return;
        }

        $pages = $this->pageRepository->findPublishedSpecials();

        $pagesSpecials = [];
        foreach ($this->specials as $key => $special) {
            $page = $this->getPage($key, $pages);
            $pagesSpecials[$key] = [
                'url' => $page !== null ? $this->router->generate($page) : null,
                'label' => $this->translator->trans($special['label'], [], $special['translation_domain']),
            ];
        }

        $data->set('page_specials', $pagesSpecials);
    }

    /**
     * @param array<PageInterface> $pages
     */
    private function getPage(string $key, array $pages): ?PageInterface
    {
        foreach ($pages as $page) {
            if ($page->getSpecial() === $key) {
                return $page;
            }
        }
        return null;
    }

    public static function getParentType(): ?string
    {
        return AreaEndpointType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'page_specials' => false,
        ]);
    }

    public static function getExtendedTypes(): array
    {
        return [ViewEndpointType::class];
    }
}
