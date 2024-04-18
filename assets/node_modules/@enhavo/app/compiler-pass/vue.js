import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
function registerComponents(builder, options) {
    let registry = builder.getDefinition(options.service);
    let definitions = builder.getDefinitionsByTagName('vue.component');
    for (let definition of definitions) {
        registry.addCall(new Call('registerComponent', [
            new Argument(definition.getTag('vue.component').getParameter('component'), 'string'),
            new Argument(definition.getName()),
        ]));
    }
}

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
function registerDirective(builder, options) {
    let registry = builder.getDefinition(options.service);
    let definitions = builder.getDefinitionsByTagName('vue.directive');
    for (let definition of definitions) {
        registry.addCall(new Call('registerDirective', [
            new Argument(definition.getTag('vue.directive').getParameter('directive'), 'string'),
            new Argument(definition.getName()),
        ]));
    }
}

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
function registerStore(builder, options) {
    let registry = builder.getDefinition(options.service);
    let definitions = builder.getDefinitionsByTagName('vue.store');
    for (let definition of definitions) {
        registry.addCall(new Call('registerStore', [
            new Argument(definition.getTag('vue.store').getParameter('store'), 'string'),
            new Argument(definition.getName()),
        ]));
    }
}

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
function registerPlugin(builder, options) {
    let registry = builder.getDefinition(options.service);
    let definitions = builder.getDefinitionsByTagName('vue.plugin');
    for (let definition of definitions) {
        registry.addCall(new Call('registerPlugin', [
            new Argument(definition.getName()),
        ]));
    }
}

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
export default function(builder, options) {
    registerComponents(builder, options);
    registerDirective(builder, options);
    registerStore(builder, options);
    registerPlugin(builder, options);
};
