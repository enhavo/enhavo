Add contact form
================

To use the ``contactForm``, just add the following code:

.. code-block:: html

    {{ contact_form('contact') }}


In the ``parameters.yml`` you can tell the ``contactForm`` who is the recipient of the contact mail and from which E-Mail address a confirmation E-Mail should be sent.

.. code-block:: yml

    contact_form_recipient: info@recipient.de
    contact_form_sender: info@sender.de