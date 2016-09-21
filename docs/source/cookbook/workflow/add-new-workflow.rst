Add new workflow
================

If you follow this step, you can add a new workflow easily to the cms.


1) Add entity to workflows
2) Add workflow to entity
3) Add worklfow to form
4) Update repository
5) Add to HTML

Add entity to workflows
-----------------------

Go to the ``enhavo.yml`` and add the entity you want to use for the workflow under ``enhavo_workflow`` ``entities`` like this:

.. code-block:: yml

    enhavo_workflow:
        entities:
            -
                class: %enhavo_acme.model.acme.class%
                label: 'acme.label.acme'
                translationDomain: EnhavoAcmeBundle
                repository: enhavo_acme.repository.acme

Add workflow to entity
----------------------

Now you have to add the workflow to the entity. First create the ``workflow_status`` column in the ``Acme.orm.yml``.

.. code-block:: yml

    oneToOne:
        workflow_status:
            cascade: ['persist', 'refresh', 'remove']
            targetEntity: Enhavo\Bundle\WorkflowBundle\Entity\WorkflowStatus

Update your entity and database with the following command.

.. code-block:: bash

    app/console doctrine:generate:entities EnhavoAcmeBundle
    app/console doctrine:schema:update --force

Add worklfow to form
--------------------

To use the workflow in the form, add the workflow-status to the ``AcmeType.php``. Therefor add the ``securityContext``  and ``dataClass`` to your constructor.

.. code-block:: php

    //Enhavo\Bundle\AcmeBundle\Form\Type

    protected $dataClass;

    protected $securityContext;

    public function __construct($securityContext, $dataClass)
    {
        $this->securityContext = $securityContext;
        $this->dataClass = $dataClass;
    }

Don't forget to add it also in the ``service.yml``.

.. code-block:: yml

    enhavo_acme_acme:
        class: %enhavo_acme.form.type.acme.class%
        arguments:
            - @security.context
            - %enhavo_acme.model.acme.class%
        tags:
            - { name: form.type }

Now you can use the securityContext in the ``AcmeType.php`` and add the workflow-status to the builder with the right entityName.

.. code-block:: php

    //Enhavo\Bundle\AcmeBundle\Form\Type

    if($this->securityContext->isGranted('WORKFLOW_ACTIVE', $this->dataClass))
    {
        $entityName = array();
        $entityName[0] = $this->dataClass;

        $builder->add('workflow_status', 'enhavo_workflow_status', array(
            'label' => 'workflow.form.label.next_state',
            'translation_domain' => 'EnhavoWorkflowBundle',
            'attr' => $entityName
        ));
    }

Update repository
-----------------

Add the following code to the entity's repository.

.. code-block:: php

    public function getEmptyWorkflowStatus()
    {
        $query = $this->createQueryBuilder('n');
        $query->where('n.workflow_status IS NULL');
        return $query->getQuery()->getResult();
    }

Add to HTML
-----------
Finally you can add the workflow to the ``acme.html.twig``.

.. code-block:: html

    {% if form.workflow_status is defined %}
        {{ form_row(form.workflow_status) }}
    {% endif %}