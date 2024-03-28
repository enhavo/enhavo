import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
export default function(builder, options) {
    let registry = builder.getDefinition(options.service);
    let definitions = builder.getDefinitionsByTagName(options.tag);

    if (!options.method) {
        options.method = 'register'
    }

    for (let definition of definitions) {
        registry.addCall(new Call(options.method, [
            new Argument(definition.getName())
        ]));
    }
};
