AppBundle
=========

Here is an example configuration for the AppBundle done in app/config/enhavo.yml.

.. code-block:: yaml

    enhavo_app:
        permission_check: false
        stylesheets:
            - '@EnhavoAppBundle/Resource/public/css/style.css'
        javascripts:
            - '@EnhavoAppBundle/Resource/public/js/bootstrap.css'
        menu:
            homepage:
                label: Homepage
                route: acme_homepage
                role: HOMEPAGE_ROLE
            download:
                label: label.download
                route: enhavo_download_download_index
                role: ROLE_ESPERANTO_DOWNLOAD_DOWNLOAD_INDEX

