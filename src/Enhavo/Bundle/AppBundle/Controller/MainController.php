<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-17
 * Time: 22:20
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Enhavo\Bundle\AppBundle\Locale\LocaleResolverInterface;
use Enhavo\Bundle\AppBundle\Menu\MenuManager;
use Enhavo\Bundle\AppBundle\Template\TemplateResolverTrait;
use Enhavo\Bundle\AppBundle\Toolbar\ToolbarManager;
use Enhavo\Bundle\AppBundle\Translation\TranslationDumper;
use Enhavo\Bundle\AppBundle\Util\StateEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    use TemplateResolverTrait;

    public function __construct(
        private MenuManager $menuManager,
        private ToolbarManager $toolbarManager,
        private string $projectDir,
        private array $brandingConfig,
        private array $toolbarPrimaryConfig,
        private array $toolbarSecondaryConfig,
        private LocaleResolverInterface $localeResolver,
        private TranslationDumper $translationDumper,
    ) {
    }

    public function indexAction(Request $request)
    {
        $state = $this->getState($request);
        $data = [
            'view' => [
                'id' => null,
            ],
            'menu' => [
                'items' => $this->menuManager->createMenuViewData(),
                'open' => true,
            ],
            'view_stack' => [
                'width' => 0,
                'views' => $state['views'],
                'storage' => $state['storage'],
            ],
            'toolbar' => [
                'primaryWidgets' => $this->toolbarManager->createWidgetsViewData($this->toolbarPrimaryConfig),
                'secondaryWidgets' => $this->toolbarManager->createWidgetsViewData($this->toolbarSecondaryConfig)
            ],
            'branding' => [
                'logo' => $this->brandingConfig['logo'],
                'enable' => $this->brandingConfig['enable'],
                'enableVersion' => $this->brandingConfig['enable_version'],
                'enableCreatedBy' => $this->brandingConfig['enable_created_by'],
                'text' => $this->brandingConfig['text'],
                'version' => $this->brandingConfig['version'],
                'backgroundImage' => $this->brandingConfig['background_image']
            ]
        ];

        return $this->render($this->resolveTemplate('admin/main/index.html.twig'), [
            'data' => $data,
            'routes' => $this->getRoutes(),
            'translations' => $this->getTranslations(),
        ]);
    }

    private function getState(Request $request)
    {
        $default = [
            'views' => [],
            'storage' => [],
        ];

        if(!$request->query->has('state')) {
            return $default;
        }
        $state = $request->query->get('state');
        $state = StateEncoder::decode($state);
        if($state === null) {
            return $default;
        }

        $views = [];
        if(isset($state['views'])) {
            $views = $state['views'];
            $id = 1;
            foreach($views as &$view) {
                if(!isset($view['component'])) {
                    $view['component'] = 'iframe-view';
                }
                if(!isset($view['id'])) {
                    $view['id'] = $id++;
                }
                $view['loaded'] = false;
            }
        }

        $storage = [];
        if(isset($state['storage'])) {
            $storage = $state['storage'];
        }

        return [
            'views' => $views,
            'storage' => $storage
        ];
    }

    private function getRoutes()
    {
        $file = $this->projectDir.'/public/js/fos_js_routes.json';
        if(file_exists($file)) {
            return file_get_contents($file);
        }
        return null;
    }

    private function getTranslations()
    {
        $translations = $this->translationDumper->getTranslations('javascript', $this->localeResolver->resolve());
        return $translations;
    }
}
