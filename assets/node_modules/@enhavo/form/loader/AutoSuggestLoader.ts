import AutoSuggestType from "@enhavo/form/type/AutoSuggestType";
import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import View from "@enhavo/app/view/View";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import Router from "@enhavo/core/Router";
import FormRegistry from "@enhavo/app/form/FormRegistry";

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
        let elements = this.findElements(element, '[data-auto-suggest]');
        for(element of elements) {
            FormRegistry.registerType(new AutoSuggestType(element, this.eventDispatcher, this.router, this.view));
        }
    }
}
