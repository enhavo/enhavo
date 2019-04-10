import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import FormType from "@enhavo/form/FormType";
import DynamicFormType from "@enhavo/form/Type/DynamicFormType";
import DynamicFormConfig from "@enhavo/form/Type/DynamicFormConfig";
import * as $ from "jquery";
import * as _ from "lodash";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";

export default class DynamicFormLoader extends AbstractLoader
{
    private application: ApplicationInterface;

    constructor(application: ApplicationInterface) {
        super();
        this.application = application;
    }

    public load(element: HTMLElement, selector: string): FormType[]
    {
        let data = [];
        let elements = this.findElements(element, selector);
        for(element of elements) {
            let config = <DynamicFormConfig>$(element).data('dynamic-config');
            _.extend(config, new DynamicFormConfig);
            config.startLoading = () => {
                this.application.getView().loading();
            };
            config.endLoading = () => {
                this.application.getView().loaded();
            };
            data.push(new DynamicFormType(element,  this.application.getRouter(), config));
        }
        return data;
    }
}