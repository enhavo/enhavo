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
    private editView: number;
    private currentType: AutoCompleteType;

    constructor(application: ApplicationInterface) {
        super();
        this.application = application;
        this.initListener()
    }

    private initListener()
    {
        this.application.getEventDispatcher().on('updated', (event: UpdatedEvent) => {
            if(event.id == this.editView && event.data != null) {
                this.currentType.addElement(event.data)
            }
        });

        this.application.getEventDispatcher().dispatch(new LoadDataEvent('autocomplete_loader'))
            .then((data: DataStorageEntry) => {
                this.editView = null;
                if(data) {
                    this.editView = data.value;
                }
            });
    }

    public insert(element: HTMLElement): void
    {
        let config = new AutoCompleteConfig();
        config.create = (type: AutoCompleteType, url: string) => {
            let label = this.application.getTranslator().trans('enhavo_app.create');
            this.application.getView().open(label, url, 'autocomplete_loader');
        };

        let elements = this.findElements(element, '[data-auto-complete-entity]');
        for(element of elements) {
            new AutoCompleteType(element, config);
        }
    }
}