const Argument = require("@enhavo/dependency-injection/container/Argument");
const Call = require("@enhavo/dependency-injection/container/Call");

/**
 * @param {ContainerBuilder} builder
 * @param {object} options
 */
module.exports = function(builder) {

    let registry = builder.getDefinition('@hotwired/stimulus/Application');
    let definitions = builder.getDefinitionsByTagName('stimulus.controller');
    for (let definition of definitions) {
        registry.addCall(new Call('register', [
            new Argument(definition.getTag('stimulus.controller').getParameter('controller'), 'string'),
            new Argument(definition.getName())
        ]));
    }
};
