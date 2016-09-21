Add fields to form
================

If you follow this step, you can easily add some fields to the ``ContactForm``.


1) Create a Model
2) Create your FormType
3) Create your HTML
4) Change enhavo.yml


Create a Model
--------------

Create a new model in your project with all the fields you want to add to your ``ContactFrom``.
This new model has to extend the enhavo contact model like this:

.. code-block:: php

    use Enhavo\Bundle\ContactBundle\Model\Contact as BaseContact;

    class Contact extends BaseContact
    {
        ...
    }


Create your FormType
---------------------------

Create a new ``FormType`` for your model and add the new fields, for example firstname and surname:

.. code-block:: php

    use Enhavo\Bundle\ContactBundle\Form\Type\ContactFormType as BaseType;

    class ContactFormType extends BaseType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            parent::buildForm($builder, $options);
            $builder->add('firstname', 'text', array(
                'label' => 'Firstname'
            ));

            $builder->add('surname', 'text',array(
                'label' => 'Surname'
            ));
        }
    }

The ``FormType`` has to extend the enhavo ``ContactFormType``.


Create your HTML
----------------

Create a new html file with the new fields in a ``form`` like this:

.. code-block:: html

    <form action="{{ path('enhavo_contact_submit', { type: type }) }}" id="contact_form" method="post">
        {{ form_row(form._token) }}
        {{ form_row(form.firstname) }}
        {{ form_row(form.surname) }}
        {{ form_row(form.email) }}
        {{ form(form.message) }}
        <button class="submit" name="sendfeedback" value="Send Message">Senden</button>
    </form>

It is important that you do not change the form action, form id, form method, button class, button name and button value.

You pobably also want to use the new fields in the recipient mail. Create a new html file and add the fields like you have done it before.
For example you can do it like this:

.. code-block:: html

    <div>
        New Message!<br>
        Firstname: {{ contact.firstname }}<br>
        Surname: {{ contact.surname }}<br>
        E-Mail: {{ contact.email }}<br>
        Message: {{ contact.message }}
    </div>

You can also create a html file for the E-Mail which goes to the sender.

Change enhavo.yml
-----------------

To use all these now, go to the ``enhavo.yml`` and change the ``enhavo_contact``:

.. code-block:: yml

    enhavo_contact:
        contact:
            model: acme\ProjectBundle\Model\Contact
            form: acme\ProjectBundle\Form\Type\ContactFormType
            template:
                form: acmeProjectBundle:Contact:form.html.twig
                recipient: acmeProjectBundle:Contact:recipient.html.twig
                sender: acmeProjectBundle:Contact:sender.html.twig
            recipient: %contact_form_recipient%
            from: %contact_form_sender%
            subject: Kontaktformular
            send_to_sender: true

Tell the ``enhavo_contact`` where to find your new model, form and templates.