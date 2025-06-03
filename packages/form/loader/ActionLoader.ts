import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import ActionFactory from "@enhavo/app/action/ActionRegistry";
import * as _ from 'lodash';
import ActionInterface from "@enhavo/app/action/ActionInterface";

export default class ActionLoader extends AbstractLoader
{
    private registry: ActionFactory;

    constructor(actionRegistry: ActionFactory) {
        super();
        this.registry = actionRegistry;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-action-button]');

        for (let element of elements) {
            let $element = $(element);
            let action: ActionInterface = $element.data('action-button');
            action = _.assign(this.registry.getFactory(action.component).createFromData(action), action);

            $element.on('click', (e) => {
                e.preventDefault();
                action.execute();
            });
        }
    }
}
