<?php

namespace Enhavo\Bundle\SearchBundle\Controller;

use Enhavo\Bundle\AppBundle\Template\TemplateManager;
use Enhavo\Bundle\SearchBundle\Engine\EngineInterface;
use Enhavo\Bundle\SearchBundle\Engine\Filter\Filter;
use Enhavo\Bundle\SearchBundle\Result\ResultConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    /** @var ResultConverter */
    private $resultConverter;

    /** @var EngineInterface */
    private $searchEngine;

    /** @var TemplateManager */
    private $templateManager;

    /**
     * @param ResultConverter $resultConverter
     * @param EngineInterface $searchEngine
     * @param TemplateManager $templateManager
     */
    public function __construct(ResultConverter $resultConverter, EngineInterface $searchEngine, TemplateManager $templateManager)
    {
        $this->resultConverter = $resultConverter;
        $this->searchEngine = $searchEngine;
        $this->templateManager = $templateManager;
    }

    /**
     * Handle search submit
     *
     * @param Request $request
     * @return Response
     */
    public function submitAction(Request $request)
    {
        $configuration = $this->createConfiguration($request);

        $term = $request->get('q', '');
        $page = $request->get('page', 1);

        $filter = new Filter();
        $filter->setTerm($term);
        $filter->setLimit(100);

        $pagination = $this->searchEngine->searchPaginated($filter);

        $pagination->setMaxPerPage($configuration->getMaxPerPage());
        $pagination->setCurrentPage($page);

        $results = $this->resultConverter->convert($pagination, $term);

        return $this->render($this->templateManager->getTemplate($configuration->getTemplate()), [
            'results' => $results,
            'pagination' => $pagination,
            'term' => $term
        ]);
    }

    private function createConfiguration(Request $request): SearchConfiguration
    {
        $configuration = new SearchConfiguration();

        $config =  $request->attributes->get('_config');

        if ($config === null) {
            throw new \InvalidArgumentException('The config attribute is mandatory');
        } elseif (!is_array($config)) {
            throw new \InvalidArgumentException('The config has to be an array');
        }

        if (!isset($config['template'])) {
            throw new \InvalidArgumentException('Template must be set');
        }
        $configuration->setTemplate($config['template']);

        if (isset($config['max_per_page'])) {
            $configuration->setMaxPerPage($config['max_per_page']);
        }

        return $configuration;
    }
}
