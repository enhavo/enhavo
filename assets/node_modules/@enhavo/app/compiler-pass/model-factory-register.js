import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

export default function(builder, options, context)
{
    let factory = builder.getDefinition(options.service);

    if (factory === null) {
        throw 'Can\'t find service "'+options.service+'"'
    }

    let componentDefinitions = builder.getDefinitionsByTagName(options.tag);
    for (let definition of componentDefinitions) {
        let className = definition.getTag(options.tag).getParameter('class');
        if (!className) {
            className = definition.import;
        }

        if (className === null) {
            throw 'Can\'t find class name for definition "'+definition.getName()+'". ' +
            'You can add the attribute "class" to your tag or use an "import" statement at your definition'
        }

        factory.addCall(new Call('registerModel', [
            new Argument(className, 'string'),
            new Argument(definition.getName()),
        ]));
    }
};
