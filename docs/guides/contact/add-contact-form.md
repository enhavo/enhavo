## Add contact form

In this section you learn how to add your own customized contact form.
Just follow the steps below and change the things to your situation.

1)  Create a Model
2)  Create your FormType
3)  Add to configuration
4)  Create your HTML

### Create a Model

Create a new model in your project with all the fields you want to add
to have. This new model could extend from the base contact model. But
it\'s up to you, if you want to create your own, just do it. But make
sure your class implements the `ContactInterface`.

```php
namespace ProjectBundle\Model;

use Enhavo\Bundle\ContactBundle\Model\Contact as BaseContact;

class Contact extends BaseContact
{
    //...
}
```

### Create your FormType

Create a new `FormType` for your model and add the fields, for example
we use here firstname and surname.

``` php
use Symfony\Component\Form\AbstractType;
use ProjectBundle\Model\Contact;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstname', 'text', array(
            'label' => 'Firstname'
        ));

        $builder->add('surname', 'text', array(
            'label' => 'Surname'
        ));

        //... other fields from parent class
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Contact::class
        ]);
    }
}
```

### Add to configuration

To tell enhavo which classes we use, you need to define your new form
and model to the `enhavo.yml`. In the `enhavo_contact` section under
`forms` you can define your new form. The type name could be any name
defined be you. It\'s good to name it like the contact model, so if you
have more contact forms in your application, its better to find which
config is apply.

```yaml
enhavo_contact:
    forms:
        contact: #this is the type name
            model: ProjectBundle\Model\Contact
            form: ProjectBundle\Form\Type\ContactFormType
            template:
                form: ProjectBundle:Contact:form.html.twig
                recipient: ProjectBundle:Contact:recipient.html.twig
                confirm: ProjectBundle:Contact:confirm.html.twig
                page: ProjectBundle:Contact:page.html.twig
            recipient: '%mailer_to%'
            from: '%mailer_from%'
            subject: Contact Form
            translation_domain: ~
            confirm_mail: true
```

### Create your HTML

In your html, just add a form that use the route `enhavo_contact_submit`
as action. Don\'t forgot to add also define the type.

```html
<form action="{{ path('enhavo_contact_submit', { type: 'contact' }) }}" id="contact_form" method="post">
    {{ form_row(form.firstname) }}
    {{ form_row(form.surname) }}
    {{ form_row(form.email) }}
    {{ form(form.message) }}
    {{ form_row(form._token) }}
    <button class="submit" name="sendfeedback" value="Send Message">Senden</button>
</form>
```

You probably also want change the recipient mail text. Just make sure
you have defined your own template under `recipient` in the form
configuration in the `enhavo.yml`. In the template file you can write
your own text now. To overwrite other mail text, just configure all to
your benefits.

```html
<div>
    New Message!<br>
    Firstname: {{ data.firstname }}<br>
    Surname: {{ data.surname }}<br>
    E-Mail: {{ data.email }}<br>
    Message: {{ data.message }}
</div>
```
