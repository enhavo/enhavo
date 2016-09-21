Download Content Type
=====================

The ``DownloadBundle`` has a content item, which can be activated. This article show how you do this.

Just add the item to ``enhavo_content``.

.. code-block:: yaml

    enhavo_content:
        items:
            download:
                model: enhavo\DownloadBundle\Entity\DownloadItem
                form: enhavo\DownloadBundle\Form\Type\DownloadItemType
                repository: enhavoDownloadBundle:DownloadItem
                template: enhavoDownloadBundle:Item:download.html.twig
                label: Download

And the fields.html into the twig config.

.. code-block:: yaml

    twig:
        form:
            resources:
                - 'enhavoDownloadBundle:Item:fields.html.twig'
