<?php
/**
 * AppViewer.php
 *
 * @since 29/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\View\Type;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\AppBundle\Translation\TranslationDumper;
use Enhavo\Bundle\AppBundle\View\AbstractViewType;
use Enhavo\Bundle\AppBundle\View\TemplateData;
use Enhavo\Bundle\AppBundle\View\ViewData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class AppViewType extends AbstractViewType
{
    /** @var Environment */
    private $twig;

    /** @var string */
    private $projectDir;

    /** @var TranslatorInterface */
    private $translator;

    /** @var RequestStack */
    private $requestStack;

    /** @var TranslationDumper */
    private $translationDumper;

    /** @var LocaleResolverInterface */
    private $localResolver;

    /** @var TemplateManager */
    private $templateManager;

    /**
     * AppViewType constructor.
     * @param Environment $twig
     * @param string $projectDir
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     * @param TranslationDumper $translationDumper
     * @param LocaleResolverInterface $localResolver
     * @param TemplateManager $templateManager
     */
    public function __construct(
        Environment $twig,
        string $projectDir,
        TranslatorInterface $translator,
        RequestStack $requestStack,
        TranslationDumper $translationDumper,
        LocaleResolverInterface $localResolver,
        TemplateManager $templateManager
    ) {
        $this->twig = $twig;
        $this->projectDir = $projectDir;
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->translationDumper = $translationDumper;
        $this->localResolver = $localResolver;
        $this->templateManager = $templateManager;
    }

    public function getType()
    {
        return 'app';
    }

    public function createViewData($options, ViewData $viewData)
    {
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

        $content = $this->twig->render($this->templateManager->getTemplate($options['template']), $parameters);

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
