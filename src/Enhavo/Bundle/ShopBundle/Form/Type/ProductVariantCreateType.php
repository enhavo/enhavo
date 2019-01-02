<?php
/**
 * ProductVariantCreate.php
 *
 * @since 23/10/16
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Enhavo\Bundle\ShopBundle\Model\ProductInterface;
use Sylius\Component\Variation\Model\VariantInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class ProductVariantCreateType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;

    /**
     * ProductVariantCreateType constructor.
     *
     * @param $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $variant = $event->getData();
            $form = $event->getForm();

            $options = [];
            if($variant instanceof VariantInterface) {
                $product = $variant->getObject();
                if($product instanceof ProductInterface) {
                    $options = $product->getOptions();
                }
            }

            $form->add('options', 'sylius_product_option_value_collection', [
                'options' => $options
            ]);
        });

        $builder->add('code', TextType::class);
    }

    public function getBlockPrefix()
    {
        return 'enhavo_shop_product_variant_create';
    }
}