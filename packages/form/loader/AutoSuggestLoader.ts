import AutoSuggestType from "@enhavo/form/type/AutoSuggestType";
import AbstractLoader from "@enhavo/form/loader/AbstractLoader";
import Router from "@enhavo/core/Router";
import FormRegistry from "@enhavo/app/form/FormRegistry";

export default class AutoCompleteLoader extends AbstractLoader
{
    private readonly router: Router;

    constructor(router: Router) {
        super();
        this.router = router;
    }

    public insert(element: HTMLElement): void
    {
        let elements = this.findElements(element, '[data-auto-suggest]');
        for(element of elements) {
            FormRegistry.registerType(new AutoSuggestType(element, this.router));
        }
    }
}
