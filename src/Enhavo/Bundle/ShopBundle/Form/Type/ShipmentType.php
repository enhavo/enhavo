<?php
/**
 * ShipmentType.php
 *
 * @since 03/03/17
 * @author gseidel
 */

namespace Enhavo\Bundle\ShopBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var string
     */
    private $shipmentClass;

    /**
     * ShipmentType constructor.
     *
     * @param $dataClass
     * @param $shipmentClass
     */
    public function __construct($dataClass, $shipmentClass)
    {
        $this->dataClass = $dataClass;
        $this->shipmentClass = $shipmentClass;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('method', 'entity', [
                'class' => $this->shipmentClass,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass
        ));
    }

    public function getName()
    {
        return 'enhavo_shop_shipment';
    }
}