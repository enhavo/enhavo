HierarchyNotCircular
====================

.. csv-table::
    :widths: 50 150

    Applies to, class
    Options,"- | :ref:`parentProperty <parent_property>`
    - | :ref:`message <message>`"
    Class, :class:`Enhavo\\Bundle\\FormBundle\\Validator\\Constraints\\HierarchyNotCircular`
    Validator, :class:`Enhavo\\Bundle\\FormBundle\\Validator\\Constraints\\HierarchyNotCircularValidator`

.. code-block:: yaml

  Enhavo\Bundle\PageBundle\Entity\Page:
      constraints:
          - Enhavo\Bundle\FormBundle\Validator\Constraints\HierarchyNotCircular:
                parentProperty: parent
                message: 'Hierarchy circle detected'


Options
-------

.. _parent_property:
parentProperty
~~~~~~~

**type**: string
**default**: parent

The parent property

.. _message:
.. |default| replace:: `Hierarchy circle detected`
.. include:: /reference/validation/option/message.rst
