import Argument from "@enhavo/dependency-injection/container/Argument.js"
import Call from "@enhavo/dependency-injection/container/Call.js"

export default function(builder, options, context)
{
    let manager = builder.getDefinition('@enhavo/app/manager/ResourceIndexManager');

    let visitors = builder.getDefinitionsByTagName('admin.form_visitor');
    for (let definition of visitors) {
        manager.addCall(new Call('addVisitor', [
            new Argument(definition.getName()),
        ]));
    }

    let themes = builder.getDefinitionsByTagName('admin.form_theme');
    for (let definition of themes) {
        manager.addCall(new Call('addTheme', [
            new Argument(definition.getName()),
        ]));
    }
};
