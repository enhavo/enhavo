import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";

export default class DynamicFormLoader extends AbstractLoader
{
    private application: ApplicationInterface;
    private currentType: AutoCompleteType;

    constructor(application: ApplicationInterface) {
        super();
        this.application = application;
        this.initListener()
    }

    private initListener()
    {
        this.application.getEventDispatcher().on('updated', (event: UpdatedEvent) => {
            this.application.getEventDispatcher().dispatch(new LoadDataEvent('autocomplete_loader'))
                .then((data: DataStorageEntry) => {
                    if(data) {
                        if(event.id == data.value && event.data != null) {
                            this.currentType.addElement(event.data)
                        }
                    }
                });
        });
    }

    public insert(element: HTMLElement): void
    {
        let config = new AutoCompleteConfig();
        config.executeAction = (type: AutoCompleteType, url: string) => {
            this.application.getView().open(url, 'autocomplete_loader');
            this.currentType = type;
        };
        config.edit = (type: AutoCompleteType, route: string, id: string) => {
            let url = this.application.getRouter().generate(route, {id: id});
            this.application.getView().open(url, 'autocomplete_loader');
            this.currentType = type;
        };

        let elements = this.findElements(element, '[data-auto-complete-entity]');
        for(element of elements) {
            new AutoCompleteType(element, config);
        }
    }
}