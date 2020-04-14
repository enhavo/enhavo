.. _send_action:

Send Action
===========

Creates a receiver with a link to the currently selected newsletter for each member, all groups added to the newsletter.
After that, the newsletter was prepared, but has not yet been sent out.
The :class:enhavo:newsletter:send command will then send all newsletters that have not been sent up to that point to
their respective receiver. To make sure that this happens regularly (e.g. at the same time every day) it is
recommended to set up a cronjob for this command in your production envirosend_newsletter

.. csv-table::
    :widths: 50 150

    Type , newsletter_send
    Options ,"- | :ref:`label <label_send_newsletter>`
    - | :ref:`icon <icon_send_newsletter>`
    - | :ref:`translation_domain <translation_domain_send_newsletter>`
    - | :ref:`permission <permission_send_newsletter>`
    - | :ref:`hidden <hidden_send_newsletter>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\SendActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`

Options
-------

.. _label_send_newsletter:
.. |default_label| replace:: `newsletter.action.send.label`
.. include:: /reference/action/option/label.rst

.. _icon_send_newsletter:
.. |default_icon| replace:: `send`
.. include:: /reference/action/option/icon.rst

.. _translation_domain_send_newsletter:
.. |default_translationDomain| replace:: `EnhavoNewsletterBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _permission_send_newsletter:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_send_newsletter:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst
