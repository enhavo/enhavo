Close Action
============

The CloseAction closes the current window and can ask the user to save previously made changes.

.. csv-table::
    :widths: 50 150

    Type , close
    Options ,"- | confirm_close_
    - | confirm_changes_close_
    - | confirm_message_close_
    - | confirm_label_ok_close_
    - | confirm_label_cancel_close_
    - | label_close_
    - | translation_domain_close_
    - | icon_close_
    - | permission_close_
    - | hidden_close_"
    Class, :class:`Enhavo\\Bundle\\AppBundle\\Action\\Type\\CloseActionType`
    Parent, `Enhavo\\Bundle\\AppBundle\\Action\\AbstractActionType`

Options
-------

.. _label_close:
.. |default_label| replace:: `label.close`
.. include:: /reference/action/option/label.rst

.. _translation_domain_close:
.. |default_translationDomain| replace:: `EnhavoAppBundle`
.. include:: /reference/action/option/translationDomain.rst

.. _icon_close:
.. |default_icon| replace:: `close`
.. include:: /reference/action/option/icon.rst

.. _permission_close:
.. |default_permission| replace:: null
.. include:: /reference/action/option/permission.rst

.. _hidden_close:
.. |default_hidden| replace:: `false`
.. include:: /reference/action/option/hidden.rst

.. _confirm_close:
.. |default_confirm| replace:: `false`
.. include:: /reference/action/option/confirm.rst

.. _confirm_changes_close:
.. |default_confirm_changes| replace:: `true`
.. include:: /reference/action/option/confirm_changes.rst

.. _confirm_message_close:
.. |default_confirm_message| replace:: `message.close.confirm`
.. include:: /reference/action/option/confirm_message.rst

.. _confirm_label_ok_close:
.. |default_confirm_label_ok| replace:: `label.ok`
.. include:: /reference/action/option/confirm_label_ok.rst

.. _confirm_label_cancel_close:
.. |default_confirm_label_cancel| replace:: `label.cancel`
.. include:: /reference/action/option/confirm_label_cancel.rst





