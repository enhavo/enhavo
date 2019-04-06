import * as $ from "jquery";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import FormType from "@enhavo/form/FormType";
import DynamicFormType from "@enhavo/form/Type/DynamicFormType";
import Router from "@enhavo/core/Router";

export default class DynamicFormLoader extends AbstractLoader
{
    private router: Router;

    constructor(router: Router) {
        super();
        this.router = router;
    }

    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            data.push(new DynamicFormType(element,  this.router));
        }
        return data;
    }
}