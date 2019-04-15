import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import FormType from "@enhavo/form/FormType";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";

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
            let config = new AutoCompleteConfig();
            config.create = (type: AutoCompleteType, url: string) => {
                this.application.getEventDispatcher()
                    .dispatch(new CreateEvent({
                        label: 'new',
                        component: 'iframe-view',
                        url: url
                    }))
            };
            data.push(new AutoCompleteType(element, config));
        }
        return data;
    }
}