Clever Reach Configuration
====

Clever Reach as default storage type 
----

If you want to use Clever Reach as default storage, put the following statement in your app/config/enhavo.yml

.. code-block:: yaml

    enhavo_newsletter:
        storage: 'cleverreach'

Clever Reach credentials
----

To use Clever Reach as your newsletter service, put the credentials in your app/config/enhavo.yml as follows:

.. code-block:: yaml

    enhavo_newsletter:
        storage:
            settings:
                cleverreach:
                    credentials:
                        client_id: <YOUR_CLIENT_ID>
                        login: <YOUR_LOGIN>
                        password: <YOUR_PASSWORD>

Clever Reach group mapping
----

Clever Reach uses group IDs to identify a group in their system. To map the group name of the Enhavo Newsletter Bundle to a Clever Reach group use the following statement (keep in mind to rename group1 and group2 to your own enhavo group name)

.. code-block:: yaml

    enhavo_newsletter:
        storage:
            settings:
                cleverreach:
                    groups:
                        mapping:
                            group1: <CR_GROUP_ID_1>
                            group2: <CR_GROUP_ID_2>