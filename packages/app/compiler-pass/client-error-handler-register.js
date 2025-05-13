import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

export default function(builder, options, context)
{
    let client = builder.getDefinition('@enhavo/app/client/Client');

    let visitors = builder.getDefinitionsByTagName('enhavo_app.client_error_handler');
    for (let definition of visitors) {

        let priority = definition.getTag('enhavo_app.client_error_handler').getParameter('priority');
        if (!priority) {
            priority = 0;
        }

        client.addCall(new Call('addErrorHandler', [
            new Argument(definition.getName()),
            new Argument(priority, 'number'),
        ]));
    }
};
