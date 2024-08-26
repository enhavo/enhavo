import AutoCompleteType from "@enhavo/form/type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import View from "@enhavo/app/view/View";
import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import Router from "@enhavo/core/Router";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class AutoCompleteLoader extends AbstractLoader
{
    private readonly eventDispatcher: FrameEventDispatcher;
    private readonly view: View;
    private readonly router: Router;

    constructor(eventDispatcher: FrameEventDispatcher, view: View, router: Router) {
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
