<?php
/**
 * WorkflowMenuBuilder.php
 *
 * @since 21/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\WorkflowBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class WorkflowMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'cycle');
        $this->setOption('label', $options, 'workflow.label.workflow');
        $this->setOption('translationDomain', $options, 'EnhavoWorkflowBundle');
        $this->setOption('route', $options, 'enhavo_workflow_workflow_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_WORKFLOW_WORKFLOW_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'workflow';
    }
}