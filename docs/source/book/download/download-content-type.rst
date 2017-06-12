Download Content Type
=====================

The ``DownloadBundle`` has a content item, which can be activated. This article show how you do this.

Just add the item to ``enhavo_grid``.

.. code-block:: yaml

    enhavo_grid:
        items:
            download:
                model: Enhavo\DownloadBundle\Entity\DownloadItem
                form: Enhavo\DownloadBundle\Form\Type\DownloadItemType
                repository: EnhavoDownloadBundle:DownloadItem
                template: EnhavoDownloadBundle:Item:download.html.twig
                label: Download

And the fields.html into the twig config.

.. code-block:: yaml

    twig:
        form:
            resources:
                - 'enhavoDownloadBundle:Item:fields.html.twig'
