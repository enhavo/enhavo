Send Test Action
================

Sends the selected newsletter to any e-mail address for testing. The address can be changed each time.
In contrast to the normal :ref:`Send-Action <send_action>`, the newsletter is sent immediately.

.. csv-table::
    :widths: 50 150

    Type , newsletter_send_test
    Options , "- | :ref:`modal <modal_send_newsletter_test>`
    - | :ref:`label <label_send_newsletter_test>`
    - | :ref:`icon <icon_send_newsletter_test>`
    - | :ref:`translation_domain <translation_domain_send_newsletter_test>`
    - | :ref:`permission <permission_send_newsletter_test>`
    - | :ref:`hidden <hidden_send_newsletter_test>`"
    Class, :class:`Enhavo\\Bundle\\NewsletterBundle\\Action\\SendTestActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\ModalActionType <modal_action>`

Options
-------

.. _modal_send_newsletter_test:

modal
~~~~~

**type**: `string`


**default**:

.. code-block:: php

    [
        'component' => 'ajax-form-modal',
        'route' => 'enhavo_newsletter_newsletter_test_form',
        'actionRoute' => 'enhavo_newsletter_newsletter_test'
    ]

In this modal the e-mail address is entered and the routes for the form and the controller are defined.

.. code-block:: yaml

    actions:
        modal:
            type: modal
            modal:
                component: my_modal_component
                route: my_modal_form_route
                actionRoute: my_modal_action_route


.. _label_send_newsletter_test:
.. |default_label| replace:: `newsletter.action.test_mail.label`
.. include:: /reference/action/option/label.rst

.. _icon_send_newsletter_test:
.. |default_icon| replace:: `send_newsletter_test`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_send_newsletter_test:
.. |default_translationDomain| replace:: `EnhavoNewsletterBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_send_newsletter_test:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_send_newsletter_test:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst