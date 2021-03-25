import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import ActionRegistry from "@enhavo/app/Action/ActionRegistry";
import * as _ from 'lodash';
import ActionInterface from "@enhavo/app/Action/ActionInterface";

export default class CheckboxLoader extends AbstractLoader
{
    private registry: ActionRegistry;

    constructor(actionRegistry: ActionRegistry) {
        super();
        this.registry = actionRegistry;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-action-button]');

        for (let element of elements) {
            let $element = $(element);
            let action: ActionInterface = $element.data('action-button');
            _.extend(action, this.registry.getFactory(action.component).createFromData(action));

            $element.on('click', (e) => {
                e.preventDefault();
                action.execute();
            });
        }
    }
}
