<?php


namespace Enhavo\Bundle\ArticleBundle\Provider;


use Enhavo\Bundle\AppBundle\Provider\ProviderInterface;
use Enhavo\Bundle\AppBundle\Type\TypeInterface;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Enhavo\Component\Type\AbstractType;

class TotalProvider extends AbstractType implements ProviderInterface, TypeInterface
{
    /** @var ArticleRepository */
    private $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get()
    {
        return count($this->repository->findAll());
    }

    public function getType()
    {
        return 'article.total';
    }

    public static function getName(): ?string
    {
        return 'article.total';
    }
}
