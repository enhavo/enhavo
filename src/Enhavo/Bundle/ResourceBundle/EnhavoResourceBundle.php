<?php

namespace Enhavo\Bundle\ResourceBundle;

use Enhavo\Bundle\ResourceBundle\Action\Action;
use Enhavo\Bundle\ResourceBundle\Action\ActionTypeInterface;
use Enhavo\Bundle\ResourceBundle\Batch\Batch;
use Enhavo\Bundle\ResourceBundle\Batch\BatchTypeInterface;
use Enhavo\Bundle\ResourceBundle\Column\Column;
use Enhavo\Bundle\ResourceBundle\Column\ColumnTypeInterface;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\CollectionCompilerPass;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\GridCompilerPass;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\InputCompilerPass;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\ResourceCompilerPass;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\ResourceManagerCompilerPass;
use Enhavo\Bundle\ResourceBundle\DependencyInjection\Compiler\ResourceExpressionCompilerPass;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionFunctionProviderInterface;
use Enhavo\Bundle\ResourceBundle\ExpressionLanguage\ResourceExpressionVariableProviderInterface;
use Enhavo\Bundle\ResourceBundle\Filter\Filter;
use Enhavo\Bundle\ResourceBundle\Filter\FilterTypeInterface;
use Enhavo\Bundle\ResourceBundle\Tab\Tab;
use Enhavo\Bundle\ResourceBundle\Tab\TabTypeInterface;
use Enhavo\Component\Type\TypeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EnhavoResourceBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
        $container->addCompilerPass(new ResourceCompilerPass());
        $container->addCompilerPass(new ResourceManagerCompilerPass());
        $container->addCompilerPass(new GridCompilerPass());
        $container->addCompilerPass(new CollectionCompilerPass());
        $container->addCompilerPass(new InputCompilerPass());
        $container->addCompilerPass(new ResourceExpressionCompilerPass());

        $container->addCompilerPass(new TypeCompilerPass('Action', 'enhavo_resource.action', Action::class));
        $container->addCompilerPass(new TypeCompilerPass('Batch', 'enhavo_resource.batch', Batch::class));
        $container->addCompilerPass(new TypeCompilerPass('Column', 'enhavo_resource.column', Column::class));
        $container->addCompilerPass(new TypeCompilerPass('Filter', 'enhavo_resource.filter', Filter::class));
        $container->addCompilerPass(new TypeCompilerPass('Tab', 'enhavo_resource.tab', Tab::class));

        $container
            ->registerForAutoconfiguration(ActionTypeInterface::class)
            ->addTag('enhavo_resource.action');

        $container
            ->registerForAutoconfiguration(BatchTypeInterface::class)
            ->addTag('enhavo_resource.batch');

        $container
            ->registerForAutoconfiguration(ColumnTypeInterface::class)
            ->addTag('enhavo_resource.column');

        $container
            ->registerForAutoconfiguration(FilterTypeInterface::class)
            ->addTag('enhavo_resource.filter');

        $container
            ->registerForAutoconfiguration(TabTypeInterface::class)
            ->addTag('enhavo_resource.tab');

        $container
            ->registerForAutoconfiguration(ResourceExpressionFunctionProviderInterface::class)
            ->addTag('enhavo_resource.expression_language_function_provider');

        $container
            ->registerForAutoconfiguration(ResourceExpressionVariableProviderInterface::class)
            ->addTag('enhavo_resource.expression_language_variable_provider');
    }
}
