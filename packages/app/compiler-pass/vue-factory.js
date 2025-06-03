import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Definition from "@enhavo/dependency-injection/container/Definition.js"
import Tag from "@enhavo/dependency-injection/container/Tag.js"
import Call from "@enhavo/dependency-injection/container/Call.js"


function createVueFactoryDefinition(builder)
{
    let definition = new Definition('@enhavo/app/vue/VueFactory');
    definition.setFrom('@enhavo/app/vue/VueFactory');
    definition.setImport('VueFactory');
    definition.setChunkName('vue')
    definition.addTag(new Tag('vue.service', {
        service: 'vueFactory'
    }));

    builder.addDefinition(definition);

    return definition;
}

export default function(builder, options, context)
{
    let factory = createVueFactoryDefinition(builder);

    let componentDefinitions = builder.getDefinitionsByTagName('vue.component');
    for (let definition of componentDefinitions) {
        factory.addCall(new Call('registerComponent', [
            new Argument(definition.getTag('vue.component').getParameter('component'), 'string'),
            new Argument(definition.getName()),
        ]));
    }

    let pluginDefinitions = builder.getDefinitionsByTagName('vue.plugin');
    for (let definition of pluginDefinitions) {
        factory.addCall(new Call('registerPlugin', [
            new Argument(definition.getName()),
        ]));
    }

    let serviceDefinitions = builder.getDefinitionsByTagName('vue.service');
    for (let definition of serviceDefinitions) {
        factory.addCall(new Call('registerService', [
            new Argument(definition.getTag('vue.service').getParameter('service'), 'string'),
            new Argument(definition.getName()),
            new Argument(definition.getTag('vue.service').getParameter('reactive'), 'boolean'),
        ]));
    }

    let directiveDefinitions = builder.getDefinitionsByTagName('vue.directive');
    for (let definition of directiveDefinitions) {
        factory.addCall(new Call('registerDirective', [
            new Argument(definition.getTag('vue.directive').getParameter('directive'), 'string'),
            new Argument(definition.getName()),
        ]));
    }
};
