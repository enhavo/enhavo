Repository Provider
============

The Repository Provider provides the result of a defined repository method.

.. csv-table::
    :widths: 50 150

    Type , total
    Required , "- | :ref:`repository <repository_provider>`
    - | :ref:`method <method_total>`"
    Class, :class:`Enhavo\\Bundle\\DashboardBundle\\Provider\\Type\\RepositoryDashboardProviderType`
    Parent, :class:`Enhavo\\Bundle\\DashboardBundle\\Provider\\AbstractDashboardProviderType`


Required
-------

.. _repository_provider:
.. include:: /reference/dashboard-widget/option/repository.rst

.. _method_total:

method
~~~~~

**type**: `string`
**default**: `null`

Define which method should be used.

.. code-block:: yaml


    provider:
        type: repository
        method: myMethod
        # ... further options
