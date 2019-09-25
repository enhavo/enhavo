Type Pattern
============

The ``Type Pattern`` is a pattern which is widley used in enhavo. It is not an official pattern like you may know
from the Gang of Four. While developing on enhavo we realize that we need a abstract workflow
to convert configurations into direct php classes. So over the time we found this pattern
in our code a try to standardize it. This pattern is a behavouir pattern.

For programmers who use enhavo as normal cms, it is not important to know how does pattern works in detail,
but it will get more interesting if you want to understand and work at the enhavo project.

The goal of this pattern is to work on objects that encapsulate the configuration they depends on and therefor
having different behaviour while using the same api.

For example, think of different filters in a system, that my configure in yaml like this:

.. code:: yaml

    title:
        type: text
        property:: title
        label: Title
    category:
        type: dropdown
        property: category
        label: Category

.. image:: /_static/image/type-pattern.png


