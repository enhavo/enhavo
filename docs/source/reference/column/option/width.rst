width
~~~~~

**type**: `integer`

Define the width of the column. The default value is ``1``. Because we are using a 12 column bootstrap grid you have to define a width between 1 and 12.
Remind that the sum of all columns in a certain table should be 12.

.. code-block:: yaml

    columns:
        myColumn:
            width: 1
            # ... further option