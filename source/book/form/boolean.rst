Boolean
=======

The form type ``enhavo_boolean`` can be used for boolean fields. It renders a true/false radio select.

FormType
--------

You can use this type just like any other form type in the FormBuilder->add() command.

.. code-block:: php

    $builder->add('public', 'enhavo_boolean', array(
        'label_true' =>  'label.yes',
        'label_false' => 'label.no',
        'default' => null
    );

Extra parameters (all optional):

- **label_true**: label for field true
- **label_false**: label for field false
- **default**: (true/false/null) The initial value to be set if the value in the resource is null. If this is null (default), none of the checkboxes will be checked.

