<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Block;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ArticleBundle\Entity\ArticleListBlock;
use Enhavo\Bundle\ArticleBundle\Factory\ArticleListFactory;
use Enhavo\Bundle\ArticleBundle\Form\Type\ArticleListBlockType as ArticleListFormType;
use Enhavo\Bundle\ArticleBundle\Repository\ArticleRepository;
use Enhavo\Bundle\BlockBundle\Block\AbstractBlockType;
use Enhavo\Bundle\BlockBundle\Model\BlockInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleListBlockType extends AbstractBlockType
{
    public function __construct(
        private readonly ArticleRepository $repository,
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    public function createViewData(BlockInterface $block, Data $data, $resource, array $options)
    {
        /** @var ArticleListBlock $block */
        $articles = $this->repository->findByCategoriesAndTags([], [], $block->getPagination(), $block->getLimit());
        $data->set('articles', $this->normalizer->normalize($articles, null, ['groups' => 'endpoint']));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'model' => ArticleListBlock::class,
            'form' => ArticleListFormType::class,
            'factory' => ArticleListFactory::class,
            'template' => 'theme/block/article-list.html.twig',
            'label' => 'article.label.article_list',
            'translation_domain' => 'EnhavoArticleBundle',
            'groups' => ['default', 'content'],
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_list';
    }
}
