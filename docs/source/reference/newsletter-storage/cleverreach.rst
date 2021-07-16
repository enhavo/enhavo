Cleverreach
===========

+-------------+----------------------------------------------------------------------+
| type        | cleverreach                                                          |
+-------------+----------------------------------------------------------------------+
| option      | - client_id                                                          |
|             | - client_secret                                                      |
|             | - global_attributes                                                  |
|             | - attributes                                                         |
|             | - groups                                                             |
+-------------+----------------------------------------------------------------------+
| class       | `Enhavo\Bundle\NewsletterBundle\Storage\Type\CleverReachStorageType` |
+-------------+----------------------------------------------------------------------+


Option
------

client_id
~~~~~~~~

**type**: `string`

This is the Client ID you can look up in your Cleverreach-Account -> My Account -> Rest-API -> OAuth-Apps.
Mostly you set this in your .env.local file of your project.

.. code-block:: yaml

    storage:
        type: cleverreach
        client_id: '%env(string:CLEVERREACH_ID)%'

client_secret
~~~~~~~~

**type**: `string`

This is the Client Secret you can look up in your Cleverreach-Account -> My Account -> Rest-API -> OAuth-Apps.
Mostly you set this in your .env.local file of your project.

.. code-block:: yaml

    storage:
        type: cleverreach
        client_secret: '%env(string:CLEVERREACH_SECRET)%'

global_attributes
~~~~~~~~

**type**: `array`

If you have defined additional (global) fields inside your Cleverreach-account, which should be considered in your subscription, you need to add them in your configuration as well.

cleverreachfieldname: That should be the name of the field in your Cleverreach-account.
modelfieldname: That's the name of the field in your NewsletterSubscriber inside your application. Note: You don't need to change your database scheme. global_attributes are stored inside a json_array data type.

.. code-block:: yaml

    storage:
        type: cleverreach
        global_attributes:
            cleverreachfieldname: modelfieldname

attributes
~~~~~~

**type**: `array`

Same like "global_attributes" with fields you have defined in your Cleverreach-account specifically for only one receiver group and not globally.

.. code-block:: yaml

    storage:
        type: cleverreach
        attributes:
            cleverreachfieldname: modelfieldname

groups
~~~~~~~~

**type**: `array`

These are the receiver groups of your Cleverreach-account your subscriber should be stored in. Each receiver group has an ID you can look up in your Cleverreach-account. This ID needs to be used here (mostly set in your .env.local file).

.. code-block:: yaml

    storage:
        type: cleverreach
        groups:
            - '%env(string:CLEVERREACH_GROUP)%'