Customize Rendering
===================

.. note::

    If you are not familiar with the symfony form rendering, we advice to read the
    `How to Customize Form Rendering <https://symfony.com/doc/current/form/form_customization.html>`_ first before you go on.


General Config
--------------

First you need to add a file in your template folder like ``admin/form/form/fields.html.twig`` and register that file in your config.

.. code-block:: yaml

    # config/packages/enhavo.yaml

    enhavo_app:
        form_themes:
            - 'admin/form/form/fields.html.twig'

Now you are ready to customize your rendering

Customize
---------

In your FormType, you have to add the function ``getBlockPrefix``

.. code-block:: php

    # src/Form/MyFormType

    class MyFormType
    {
        // ...
        public function getBlockPrefix()
        {
            return 'my_form'
        }
    }

Now you can add a block in your template file, which you registered before.

.. code-block:: twig

    {# admin/form/form/fields.html.twig #}

    {% block my_form_widget %}
        <div class="row">
            <div class="col-md-6">{{ form_row(form.name) }}</div>
            <div class="col-md-6">{{ form_row(form.date) }}</div>
        </div>
    {% endblock }





