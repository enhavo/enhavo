Currency
========

The type '' enhavo currency'' can be used for any field, where a currency is needed.

Features
--------

The enhavo currency type converts the database integer value into a full price and vice versa. It works like a natural price field and not like a database field, it is not needed to type 1200, to express 12,00 EUR, which makes it very comfortable for the user.


FormType
--------

.. code-block:: php

    <?php

    use Enhavo\Bundle\FormBundle\Form\Type\CurrencyType;

    class YourShopClass extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('price', CurrencyType::class, array(
                'label' => 'Price'
            ));
        }
    }
