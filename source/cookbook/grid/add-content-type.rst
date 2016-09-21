Add content type
================

If you follow these steps, you can easily add any content type to the cms.

1) Create an Entity
2) Create a FormType
3) Create a template file
4) Add configuration

As an example we will add a new content type called youtube. In our case, the youtube type contains these three
parameter: ``url``, ``title`` and ``author``.

Entity
------

First we add an entity implementing the ``ItemTypeInterface``. The entity must be registered with Doctrine, because
GridBundle will call ``persist`` to this object. If you use a non doctrine entity, you will receive errors trying to
save the content.

.. code-block:: php

    <?php

    namespace Acme\FooBundle\Entity;

    use Enhavo\GridBundle\Item\ItemTypeInterface;

    class Youtube implements ItemTypeInterface
    {
        private $id;
        private $url;
        private $title;
        private $author;

        public function getId()
        {
            return $this->id;
        }

        public function getUrl()
        {
            return $this->url;
        }

        public function setUrl($url)
        {
            $this->url = $url;
            return $this;
        }

        //...setter and getter for title and author
    }


FormType
--------

The FormType should extend from ``ItemFormType``.

.. code-block:: php

    <?php

    namespace Acme\FooBundle\Form\Type;

    use Enhavo\GridBundle\Item\ItemFormType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;

    class YoutubeType extends ItemFormType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder->add('url', 'text');
            $builder->add('title', 'text');
            $builder->add('author', 'text');
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'Acme\FooBundle\Entity\Youtube'
            ));
        }

        public function getName()
        {
            return 'acme_foo_item_youtube';
        }
    }

If we want to change the style in our form we have to add a new block in separate twig file...

.. code-block:: twig

    #fields.html.twig
    {% block acme_foo_item_youtube_widget %}
    <div class="padding">
        {{ form_widget(form.url) }}
    </div>
        <div class="padding">
        {{ form_widget(form.title) }}
    </div>
        <div class="padding">
        {{ form_widget(form.author) }}
    </div>
    {% endblock %}

... and add this file to the twig configuration in config.yml.

.. code-block:: yaml

    twig:
        form:
            resources:
                - 'AcmeFooBundle:Form:fields.html.twig'

Template
--------

To render the content type in the frontend, we create a simple twig file somewhere in our bundle. The object will be
passed to the template as the parameter ``data``. In our case, it will be an instance of the class ``Youtube``.

.. code-block:: twig

    {# AcmeFooBundle:ItemType:youtube.html.twig #}

    <h2>{{ data.title }}<h2>
    <iframe width="560" height="315" src="{{ data.url }}" frameborder="0" allowfullscreen></iframe>
    <div>by {{ data.author }}</div>

Configuration
-------------

Finally we need to add the youtube type to the configuration in app/config/enhavo.yml under ``enhavo_grid.items``.

The option ``label`` is optional. It is displayed in the context menu where you can add new items to your content.

.. code-block:: yaml

    enhavo_grid:
        items:
            youtube:
                model: Acme\FooBundle\Entity\Youtube
                form: Acme\FooBundle\Form\Type\YoutubeType
                repository: AcmeFooBundle:Youtube
                template: AcmeFooBundle:ItemType:youtube.html.twig
                label: Youtube
