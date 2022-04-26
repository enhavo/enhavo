<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 16.09.20
 * Time: 11:42
 */

namespace Enhavo\Bundle\ShopBundle\Repository;

use Doctrine\ORM\QueryBuilder;
use Enhavo\Bundle\AppBundle\Filter\FilterQuery;
use Enhavo\Bundle\ContentBundle\Repository\ContentRepository;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Sylius\Component\Product\Repository\ProductVariantRepositoryInterface;
use Sylius\Bundle\ProductBundle\Doctrine\ORM\ProductVariantRepository as SyliusProductVariantRepository;

class ProductVariantRepository extends SyliusProductVariantRepository implements ProductVariantRepositoryInterface
{
    /**
     * @param string $locale
     * @param $productId
     * @return QueryBuilder
     */
    public function createQueryBuilderByProductId(string $locale, $productId): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.locale = :locale')
            ->andWhere('o.product = :productId')
            ->setParameter('locale', $locale)
            ->setParameter('productId', $productId)
            ;
    }

    /**
     * @param string $locale
     * @param string $productCode
     * @return QueryBuilder
     */
    public function createQueryBuilderByProductCode(string $locale, string $productCode): QueryBuilder
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->innerJoin('o.product', 'product')
            ->andWhere('translation.locale = :locale')
            ->andWhere('product.code = :productCode')
            ->setParameter('locale', $locale)
            ->setParameter('productCode', $productCode)
            ;
    }

    /**
     * @param string $name
     * @param string $locale
     * @return array
     */
    public function findByName(string $name, string $locale): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.name = :name')
            ->andWhere('translation.locale = :locale')
            ->setParameter('name', $name)
            ->setParameter('locale', $locale)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $name
     * @param string $locale
     * @param ProductInterface $product
     * @return array
     */
    public function findByNameAndProduct(string $name, string $locale, ProductInterface $product): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation')
            ->andWhere('translation.name = :name')
            ->andWhere('translation.locale = :locale')
            ->andWhere('o.product = :product')
            ->setParameter('name', $name)
            ->setParameter('locale', $locale)
            ->setParameter('product', $product)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param string $code
     * @param string $productCode
     * @return null|ProductVariantInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByCodeAndProductCode(string $code, string $productCode): ?ProductVariantInterface
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.product', 'product')
            ->andWhere('product.code = :productCode')
            ->andWhere('o.code = :code')
            ->setParameter('productCode', $productCode)
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function findByCodesAndProductCode(array $codes, string $productCode): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.product', 'product')
            ->andWhere('product.code = :productCode')
            ->andWhere('o.code IN (:codes)')
            ->setParameter('productCode', $productCode)
            ->setParameter('codes', $codes)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $id
     * @param $productId
     * @return null|ProductVariantInterface
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdAndProductId($id, $productId): ?ProductVariantInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.product = :productId')
            ->andWhere('o.id = :id')
            ->setParameter('productId', $productId)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param string $phrase
     * @param string $locale
     * @param string $productCode
     * @return array
     */
    public function findByPhraseAndProductCode(string $phrase, string $locale, string $productCode): array
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();

        return $this->createQueryBuilder('o')
            ->innerJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->innerJoin('o.product', 'product')
            ->andWhere('product.code = :productCode')
            ->andWhere($expr->orX(
                'translation.name LIKE :phrase',
                'o.code LIKE :phrase'
            ))
            ->setParameter('phrase', '%' . $phrase . '%')
            ->setParameter('locale', $locale)
            ->setParameter('productCode', $productCode)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param FilterQuery $filterQuery
     * @param $productId
     * @return mixed
     */
    public function findByProductId($productId, FilterQuery $filterQuery)
    {
        $filterQuery->addWhere('product',FilterQuery::OPERATOR_EQUALS, $productId);
        return $this->filter($filterQuery);
    }
}

