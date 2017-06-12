AppBundle
=========

Here is an example configuration for the AppBundle done in app/config/enhavo.yml.

.. code-block:: yaml

    enhavo_app:
        stylesheets:
            - '@EnhavoAppBundle/Resource/public/css/style.css'
        javascripts:
            - '@EnhavoAppBundle/Resource/public/js/bootstrap.css'
        apps:
            - 'project/app/Homepage'
        menu:
            homepage:
                type: base
                label: Homepage
                translationDomain: ~
                route: project_homepage_index
                role: PROJECT_HOMEPAGE_ROLE
            download:
                type: download

