<?php
/**
 * Created by PhpStorm.
 * User: philippsester
 * Date: 26.08.20
 * Time: 19:44
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\ProductBundle\Form\Type\ProductOptionValueType as SymfonyProductOptionValueType;

class ProductOptionValueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->remove('translations');
        $builder->addEventSubscriber(new AddCodeFormSubscriber());
    }

    public function getParent()
    {
        return SymfonyProductOptionValueType::class;
    }
}
