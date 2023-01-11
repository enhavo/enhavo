const Argument = require("@enhavo/dependency-injection/container/Argument");
const Call = require("@enhavo/dependency-injection/container/Call");

module.exports = function(builder, options, context)
{
    let factory = builder.getDefinition('@enhavo/vue-form/form/FormFactory');

    let componentDefinitions = builder.getDefinitionsByTagName('form.model');
    for (let definition of componentDefinitions) {
        factory.addCall(new Call('registerModel', [
            new Argument(definition.getTag('form.model').getParameter('component'), 'string'),
            new Argument(definition.getName()),
        ]));
    }
};
