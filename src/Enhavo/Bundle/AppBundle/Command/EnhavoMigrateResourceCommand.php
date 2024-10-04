<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-05-24
 * Time: 18:14
 */

namespace Enhavo\Bundle\AppBundle\Command;

use Enhavo\Bundle\AppBundle\Template\TemplateResolverInterface;
use Enhavo\Bundle\ResourceBundle\Collection\ListCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;

class EnhavoMigrateResourceCommand extends Command
{
    public function __construct(
        private $syliusResources,
        private RouterInterface $router,
        private TemplateResolverInterface $templateResolver,
        private Environment $twig,
    )
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('enhavo:migrate:resource')
            ->addArgument('resource_name', InputArgument::REQUIRED,'Sylius resource name')
            ->setDescription('Create output for files')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resourceName = $input->getArgument('resource_name');

        $errors = [];

        if (!isset($this->syliusResources[$resourceName])) {
            $output->writeln('Resource not exists');
            return Command::FAILURE;
        }

        $resourceConfig = $this->syliusResources[$resourceName];

        $indexRoute = $this->getRoute($resourceName, 'index');
        $createRoute = $this->getRoute($resourceName, 'create');
        $updateRoute = $this->getRoute($resourceName, 'update');
        $tableRoute = $this->getRoute($resourceName, 'table');
        $dataRoute = $this->getRoute($resourceName, 'data');
        $batchRoute = $this->getRoute($resourceName, 'batch');

        $gridActions = $indexRoute ? $this->getGridActions($indexRoute) : [];
        $createActions = $createRoute ? $this->getCreateActions($createRoute) : [];
        $columns = $tableRoute || $dataRoute ? $this->getColumns($tableRoute ?? $dataRoute) : [];
        $filters = $tableRoute || $dataRoute ? $this->getFilters($tableRoute ?? $dataRoute) : [];
        $batches = $batchRoute ? $this->getBatches($batchRoute) : [];
        $tabs = $createRoute ? $this->getTabs($createRoute, $errors) : [];
        $form = $createRoute ? $this->getForm($createRoute, $resourceConfig) : $resourceConfig['classes']['form'];
        $formOptions = $createRoute ? $this->getFormOptions($createRoute) : [];

        $output->writeln('<info>------ Resources config ------</info>');

        $config = $this->getResourceConfig(
            $resourceName,
            $resourceConfig,
            $gridActions,
            $columns,
            $createActions,
            $tabs,
            $form,
            $formOptions,
            $filters,
            $batches,
            $dataRoute
        );
        $output->writeln(Yaml::dump($config, 8));

        $output->writeln('<info>------ Admin Api Routes ------</info>');

        $apiRoutes = $this->getApiRoutesConfig($resourceName, false, false);
        foreach ($apiRoutes as $key => $apiRoute) {
            $output->writeln(Yaml::dump([$key => $apiRoute], 8));
        }

        $output->writeln('<info>------ Admin Routes ------</info>');

        $adminRoutes = $this->getAdminRoutesConfig($resourceName, false);
        foreach ($adminRoutes as $key => $adminRoute) {
            $output->writeln(Yaml::dump([$key => $adminRoute], 8));
        }

        if (!$indexRoute) {
            $output->writeln('<comment>No index route found!</comment>');
        }

        if (!$createRoute) {
            $output->writeln('<comment>No create route found!</comment>');
        }

        if (!$updateRoute) {
            $output->writeln('<comment>No update route found!</comment>');
        }

        if (!$tableRoute && !$dataRoute) {
            $output->writeln('<comment>No table or data route found!</comment>');
        }

        if (!$batchRoute) {
            $output->writeln('<comment>No batch route found!</comment>');
        }

        if ($updateRoute && $createRoute && $this->checkDifferentCreateUpdateTabs($updateRoute, $createRoute)) {
            $output->writeln('<comment>Create and update route have different tab options, maybe a second input is needed</comment>');
        }

        return Command::SUCCESS;
    }

    private function getRoute($resourceName, $routeName)
    {
        $routePrefix = str_replace('.', '_', $resourceName);
        $routeName = $routePrefix . '_' . $routeName;
        return $this->router->getRouteCollection()->get($routeName);
    }

    private function getGridActions($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['actions'])) {
            return $defaults['_sylius']['viewer']['actions'];
        }

        return [];
    }

    private function getCreateActions($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['actions'])) {
            return $defaults['_sylius']['viewer']['actions'];
        }

        return [];
    }

    private function getColumns($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['columns'])) {
            return $defaults['_sylius']['viewer']['columns'];
        }

        return [];
    }

    private function getFilters($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['filters'])) {
            return $defaults['_sylius']['filters'];
        }

        return [];
    }

    private function getBatches($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['batches'])) {
            return $defaults['_sylius']['batches'];
        }

        return [];
    }

    private function getTabs($route, &$errors)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['tabs'])) {
            $tabs = $defaults['_sylius']['viewer']['tabs'];
            foreach ($tabs as $key => &$tab) {
                $tab['type'] = 'form';
                $template = $this->templateResolver->resolve($tab['template']);

                $arrangement = [];
                try {
                    $path = $this->twig->resolveTemplate($template)->getSourceContext()->getPath();
                    $content = file_get_contents($path);
                    $lines = explode("\n", $content);
                    foreach ($lines as $line) {
                        if (preg_match('/form_[a-z]+\(form.([a-z0-9]+)\)/', $line, $matches)) {
                            $arrangement[] = $matches[1];
                        }
                    }
                } catch (\Exception $exception) {
                    $errors[] = sprintf('<warning>Can\'t find arrangement for tab "%s"</warning>', $key);
                }

                if (count($arrangement)) {
                    $tab['arrangement'] = $arrangement;
                }

                unset($tab['template']);
                if (isset($tab['full_width'])) {
                    unset($tab['full_width']);
                }
            }
            return $tabs;
        }

        return [];
    }

    private function getForm($route, $resourceConfig)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['form']['type'])) {
            return $defaults['_sylius']['viewer']['form']['type'];
        }

        return $resourceConfig['classes']['form'];
    }

    private function getFormOptions($route)
    {
        $defaults = $route->getDefaults();
        if (isset($defaults['_sylius']['viewer']['form']['options'])) {
            return $defaults['_sylius']['viewer']['form']['options'];
        }

        return [];
    }

    private function checkDifferentCreateUpdateTabs($createRoute, $updateRoute)
    {
        return false;
    }

    private function getResourceConfig(
        $resourceName,
        $resourceConfig,
        $gridActions,
        $columns,
        $createActions,
        $tabs,
        $form,
        $formOptions,
        $filters,
        $batches,
        ?Route $listRoute,
    )
    {
        $data = [
            'enhavo_resource' => [
                'resources' => [
                    $resourceName => [
                        'classes' => [
                            'model' => $resourceConfig['classes']['model'],
                            'factory' =>  $resourceConfig['classes']['factory'],
                            'repository' =>  $resourceConfig['classes']['repository'],
                        ],
                    ],
                ],
                'grids' => [
                    $resourceName => [
                        'extends' => 'enhavo_resource.grid',
                        'resource' => $resourceName,
                        'actions' => $gridActions,
                        'filters' => $filters,
                        'columns' => $columns,
                        'batches' => $batches,
                    ],
                ],
                'inputs' => [
                    $resourceName => [
                        'extends' => 'enhavo_resource.input',
                        'resource' => $resourceName,
                        'form' => $form,
                        'form_options' => $formOptions,
                        'actions' => $createActions,
                        'tabs' => $tabs,
                    ],
                ],
            ],
        ];

        if ($listRoute) {
            $data['enhavo_resource']['grids'][$resourceName]['collection'] = [
                'class' => ListCollection::class,
            ];

            if (isset($listRoute->getDefaults()['_sylius']['sortable'])) {
                $data['enhavo_resource']['grids'][$resourceName]['collection']['sortable'] = !!$listRoute->getDefaults()['_sylius']['sortable'];
            }

            if (isset($listRoute->getDefaults()['_sylius']['viewer']['position_property'])) {
                $data['enhavo_resource']['grids'][$resourceName]['collection']['position_property'] = $listRoute->getDefaults()['_sylius']['viewer']['position_property'];
            }

            if (isset($listRoute->getDefaults()['_sylius']['viewer']['parent_property'])) {
                $data['enhavo_resource']['grids'][$resourceName]['collection']['parent_property'] = $listRoute->getDefaults()['_sylius']['viewer']['parent_property'];
            }

            if (isset($listRoute->getDefaults()['_sylius']['viewer']['children_property'])) {
                $data['enhavo_resource']['grids'][$resourceName]['collection']['children_property'] = $listRoute->getDefaults()['_sylius']['viewer']['children_property'];
            }
        }

        return $data;
    }

    private function getApiRoutesConfig($resourceName, $duplicate, $preview)
    {
        $split = explode('.', $resourceName);
        $company = strtolower($split[0]);
        $resource = strtolower($split[1]);

        $routes = [];

        $routes[$company . '_admin_api_' .  $resource . '_index'] = [
            'path' => '/' . $resource . '/index',
            'methods' => ['GET'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_index',
                    'grid' => $resourceName,
                ]
            ],
        ];

        $routes[$company . '_admin_api_' .  $resource . '_list'] = [
            'path' => '/' . $resource . '/list',
            'methods' => ['GET', 'POST'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_list',
                    'grid' => $resourceName,
                ]
            ],
        ];

        $routes[$company . '_admin_api_' .  $resource . '_create'] = [
            'path' => '/' . $resource . '/create',
            'methods' => ['GET', 'POST'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_create',
                    'input' => $resourceName,
                ]
            ],
        ];

        $routes[$company . '_admin_api_' .  $resource . '_update'] = [
            'path' => '/' . $resource . '/update/{id}',
            'methods' => ['GET', 'POST'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_update',
                    'input' => $resourceName,
                ]
            ],
        ];

        $routes[$company . '_admin_api_' .  $resource . '_delete'] = [
            'path' => '/' . $resource . '/delete/{id}',
            'methods' => ['DELETE'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_delete',
                    'input' => $resourceName,
                ]
            ],
        ];

        $routes[$company . '_admin_api_' .  $resource . '_batch'] = [
            'path' => '/' . $resource . '/batch',
            'methods' => ['POST'],
            'defaults' => [
                '_expose' => 'admin_api',
                '_endpoint' => [
                    'type' => 'resource_batch',
                    'grid' => $resourceName,
                ]
            ],
        ];

        if ($duplicate) {
            $routes[$company . '_admin_api_' .  $resource . '_duplicate'] = [
                'path' => '/' . $resource . '/duplicate/{id}',
                'methods' => ['POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_duplicate',
                        'input' => $resourceName,
                    ]
                ],
            ];
        }

        if ($preview) {
            $routes[$company . '_admin_api_' . $resource . '_preview'] = [
                'path' => '/' . $resource . '/preview/{id}',
                'methods' => ['POST'],
                'defaults' => [
                    '_expose' => 'admin_api',
                    '_endpoint' => [
                        'type' => 'resource_preview',
                        'input' => $resourceName,
                        'endpoint' => [
                            'type' => 'null',
                            'resource' => 'expr:resource',
                            'preview' => true,
                        ]
                    ]
                ],
            ];
        }

        return $routes;
    }

    private function getAdminRoutesConfig($resourceName, $preview)
    {
        $split = explode('.', $resourceName);
        $company = strtolower($split[0]);
        $resource = strtolower($split[1]);

        $routes = [];


        $routes[$company . '_admin_' .  $resource . '_index'] = [
            'path' => '/' . $resource . '/index',
            'defaults' => [
                '_expose' => 'admin',
                '_vue' => [
                    'component' => 'resource-index',
                    'groups' => 'admin',
                    'meta' => [
                        'api' => $company . '_admin_api_' .  $resource . '_index'
                    ],
                ],
                '_endpoint' => [
                    'type' => 'admin',
                ]
            ],
        ];

        $routes[$company . '_admin_' .  $resource . '_create'] = [
            'path' => '/' . $resource . '/create',
            'defaults' => [
                '_expose' => 'admin',
                '_vue' => [
                    'component' => 'resource-input',
                    'groups' => 'admin',
                    'meta' => [
                        'api' => $company . '_admin_api_' .  $resource . '_create'
                    ],
                ],
                '_endpoint' => [
                    'type' => 'admin',
                ]
            ],
        ];

        $routes[$company . '_admin_' .  $resource . '_update'] = [
            'path' => '/' . $resource . '/update/{id}',
            'defaults' => [
                '_expose' => 'admin',
                '_vue' => [
                    'component' => 'resource-input',
                    'groups' => 'admin',
                    'meta' => [
                        'api' => $company . '_admin_api_' .  $resource . '_update'
                    ],
                ],
                '_endpoint' => [
                    'type' => 'admin',
                ]
            ],
        ];

        return $routes;
    }
}
