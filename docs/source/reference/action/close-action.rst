Close Action
============

The CloseAction closes the current window and can ask the user to save previously made changes.

.. csv-table::
    :widths: 50 150

    Type , close
    Inherited options, "- | :ref:`label <label>`
    - | :ref:`translation_domain <translation_domain>`
    - | :ref:`icon <icon>`
    - | :ref:`permission <permission>`
    - | :ref:`hidden <hidden>`"
    Options ,"- | :ref:`confirm <confirm>`
    - | :ref:`confirm_changes <confirm_changes>`
    - | :ref:`confirm_message <confirm_message>`
    - | :ref:`confirm_label_ok <confirm_label_ok>`
    - | :ref:`confirm_label_cancel <confirm_label_cancel>`"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\CloseActionType`
    Parent, :ref:`Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType <abstract-action>`


Inherited Option
----------------

.. _label:
.. |default_label| replace:: `label.close`
.. include:: /reference/action/option/label.rst

.. _translation_domain:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon:
.. |default_icon| replace:: `close`
.. include:: /reference/action/option/icon.rst

.. _permission:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

Option
------

.. _confirm:
.. |default_confirm| replace:: `false`
.. include:: /reference/action/option/confirm.rst

.. _confirm_changes:
.. |default_confirm_changes| replace:: `true`
.. include:: /reference/action/option/confirm_changes.rst

.. _confirm_message:
.. |default_confirm_message| replace:: `message.close.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst


