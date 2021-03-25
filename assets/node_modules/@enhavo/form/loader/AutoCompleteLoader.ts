import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import Router from "@enhavo/core/Router";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class AutoCompleteLoader extends AbstractLoader
{
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly router: Router;

    constructor(eventDispatcher: EventDispatcher, view: View, router: Router) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.router = router;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-auto-complete-entity]');
        for(element of elements) {
            FormRegistry.registerType(new AutoCompleteType(element, this.eventDispatcher, this.router, this.view));
        }
    }
}
