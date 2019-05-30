<?php
/**
 * ArticleCategoryWidget.php
 *
 * @since 07/07/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ArticleBundle\Widget;

use Enhavo\Bundle\AppBundle\Widget\AbstractWidgetType;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleStreamType extends AbstractWidgetType
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(ArticleRepository $repository, RequestStack $requestStack)
    {
        $this->repository = $repository;
        $this->requestStack = $requestStack;
    }

    public function createViewData(array $options, $resource = null)
    {
        $categories = $options['categories'];
        if($options['include_descendants'] && $options['categories'] > 0) {
            $categories = [];
            foreach($options['categories'] as $category) {
                $categories[] = $category;
                foreach($category->getDescendants() as $descendant) {
                    $categories[] = $descendant;
                }
            }
        }

        $articles = $this->repository->findByCategoriesAndTags($categories, $options['tags'], $options['pagination'],  $options['limit']);

        if($articles instanceof Pagerfanta) {
            $page = $options['page'];
            if($page === null) {
                $page = $this->requestStack->getCurrentRequest()->get($options['page_parameter']);
            }
            $articles->setCurrentPage($page ?  $page : 1);
        }

        return [
            'articles' => $articles,
        ];
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'template' => 'EnhavoArticleBundle:Widget:article_stream.html.twig',
            'categories' => [],
            'tags' => [],
            'pagination' => true,
            'limit' => 10,
            'page' => null,
            'page_parameter' => 'page',
            'include_descendants' => true
        ]);
    }

    public function getType()
    {
        return 'article_stream';
    }
}
