Create Button
=============

Create class
------------

First you have to define a class that implements the ``ButtonInterface``.
On that interface you have to provide a ``getType``. This is the type name we use later display this button.
If you want to have some additional helper methods we recommend to extend from ``AbstractType``
that provide some helpers like ``renderTemplate``. Normally a button just use a different symbol a html type.
The rest is mostly implement in the javascript functionality, so you can use the default template
``EnhavoAppBundle:Button:default.html.twig`` for rendering.

.. code-block:: php

    <?php

    namespace MyBundle\Button;

    use Enhavo\Bundle\AppBundle\Button\ButtonInterface;
    use Enhavo\Bundle\AppBundle\Type\AbstractType;

    class DownloadButton extends AbstractType implements ButtonInterface
    {
        public function render($options, $resource)
        {
            return $this->renderTemplate('EnhavoAppBundle:Button:default.html.twig', [
                'type' => $this->getType(),
                'route' => 'my_download_route',
                'icon' => 'download',
                'display' => true,
                'role' => null,
                'label' => 'label.download',
                'translationDomain' => null
            ]);
        }

        public function getType()
        {
            return 'download';
        }
    }


Add to service
--------------

Now you have to add the created class to the dependency injection container.

.. code-block:: yaml

    #service.yml
    my_bundle.button.download:
        class: MyBundle\Button\DownloadButton
        calls:
            - [setContainer, ['@service_container']]
        tags:
            - { name: enhavo.button }


Add js event
------------

Add a javascript file to enhavo and listen to the event ``formOpenAfter`` to bind the button.


.. code-block:: javascript

    $(function() {
      $(document).on('formOpenAfter', function(event, form) {
        $(form).find('[data-button][data-type=download]').click(function(e) {
          e.preventDefault();
          e.stopPropagation();
          //Do here what you want
        });
      });
    });
