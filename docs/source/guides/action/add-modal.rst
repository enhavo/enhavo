Add Modal
=========

.. code-block:: yaml

    actions:
        assign:
            type: modal
            modal:
                type: ajax-form-modal
                route: assign_modal


.. code-block:: yaml

    assign_modal:
        _controller: enhavo_app.controller.modal:formAction
        _defaults:
            form: App\Form\AssignType


.. code-block:: php

    namespace App\Form;

    class AssignType
    {
        public function buildForm(FormBuilder $builder) {
            $builder->add();
        }
    }
