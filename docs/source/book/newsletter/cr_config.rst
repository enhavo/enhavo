Clever Reach Config
====

Storage Type
----

There are currently two storage types - 'local' and 'clever_reach'. 'local' is the default value - you need no entry in your app/config/config.yml.
If you want to use 'clever_reach', put the following statement in your app/config/config.yml and (follow the instructions for the credentials and group!):

.. code-block:: yaml

    enhavo_newsletter:
        storage: 'clever_reach'

Clever Reach credentials
----

To use Clever Reach as your newsletter service, put the credentials in your app/config/config.yml as follows:

.. code-block:: yaml

    enhavo_newsletter:
        resources:
            newsletter:
                options:
                    credentials:
                        client_id: '<YOUR_CLIENT_ID>'
                        login: '<YOUR_LOGIN>'
                        password: '<YOUR_PASSWORD>'

Clever Reach group
----

You have to define a Clever Reach group, to which you add your new subscribers (find your group ID on the Clever Reach website).
Define it as follows:

.. code-block:: yaml

    enhavo_newsletter:
        resources:
            newsletter:
                options:
                    group: '<YOUR_GROUP_ID>'