AppBundle
=========

.. note::

  This article outdated and may contain information that are not in use any more

Here is an example configuration for the AppBundle done in app/config/enhavo.yml.

.. code-block:: yaml

    enhavo_app:
        menu:
            homepage:
                type: base
                label: Homepage
                translationDomain: ~
                route: project_homepage_index
                role: PROJECT_HOMEPAGE_ROLE
            download:
                type: download

