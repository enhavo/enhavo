import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

export default function(builder, options, context)
{
    let factory = builder.getDefinition('@enhavo/vue-form/form/FormFactory');

    let componentDefinitions = builder.getDefinitionsByTagName('form.model');
    for (let definition of componentDefinitions) {
        let className = definition.getTag('form.model').getParameter('class');
        if (!className) {
            className = definition.import;
        }

        factory.addCall(new Call('registerModel', [
            new Argument(className, 'string'),
            new Argument(definition.getName()),
        ]));
    }
};
