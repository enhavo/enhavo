<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\AppBundle\Routing\RouteCollectorInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateResolver;
use Enhavo\Bundle\AppBundle\Translation\TranslationDumper;
use Enhavo\Bundle\AppBundle\Twig\TwigRouter;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class AppViewType extends AbstractViewType
{
    public function __construct(
        private readonly Environment $twig,
        private readonly string $projectDir,
        private readonly TranslatorInterface $translator,
        private readonly RequestStack $requestStack,
        private readonly TranslationDumper $translationDumper,
        private readonly LocaleResolverInterface $localResolver,
        private readonly TemplateResolver $templateResolver,
        private readonly TwigRouter $twigRouter,
        private readonly RouteCollectorInterface $routeCollector,
        private readonly NormalizerInterface $normalizer,
    ) {

    }

    public function getType()
    {
        return 'app';
    }

    public function createViewData($options, ViewData $viewData)
    {
        $routesCollection = $this->routeCollector->getRouteCollection('admin');
        $this->twigRouter->addRouteCollection($routesCollection);
        $viewData->set('routes', $this->normalizer->normalize($routesCollection, null, ['groups' => 'endpoint']));

        $viewData['view'] = [
            'id' => $this->requestStack->getMainRequest()->get('view_id'),
            'label' => $this->translator->trans($options['label'], [], $options['translation_domain'])
        ];
    }

    public function createTemplateData($options, ViewData $viewData, ViewData $templateData)
    {
        $templateData['controller'] = $options['controller'];
        $templateData['application'] = $options['application'];
        $templateData['component'] = $options['component'];
    }

    public function getResponse($options, Request $request, ViewData $viewData, TemplateData $templateData): Response
    {
        $parameters = [
            'data' => $viewData->normalize(),
            'translations' => $this->translationDumper->getTranslations('javascript', $this->localResolver->resolve()),
            'routes' => $this->getRoutes(),
            'entrypoints' => is_array($options['entrypoint']) ? $options['entrypoint'] : [$options['entrypoint']]
        ];

        $parameters = array_merge($parameters, $templateData->normalize());

        $content = $this->twig->render($this->templateResolver->resolve($options['template']), $parameters);

        return new Response($content);
    }

    private function getRoutes()
    {
        $file = $this->projectDir.'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'label' => '',
            'translation_domain' => null,
            'entrypoint' => 'enhavo/application',
            'controller' => 'application',
            'template' => 'admin/view/app.html.twig'
        ]);

        $optionsResolver->setRequired(['application', 'component']);
    }
}
