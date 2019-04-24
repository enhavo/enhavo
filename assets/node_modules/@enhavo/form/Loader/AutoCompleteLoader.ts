import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import ApplicationInterface from "@enhavo/app/ApplicationInterface";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";
import CreateEvent from "@enhavo/app/ViewStack/Event/CreateEvent";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import ViewInterface from "@enhavo/app/ViewStack/ViewInterface";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";

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
        this.application.getEventDispatcher().on('removed', (event: RemovedEvent) => {
            if(event.id == this.editView) {
                this.editView = null;
            }
        });

        this.application.getEventDispatcher().on('updated', (event: UpdatedEvent) => {
            if(event.id == this.editView && event.data != null) {
                this.currentType.addElement(event.data)
            }
        });

        let key = this.getEditKey();
        this.application.getEventDispatcher().dispatch(new LoadDataEvent(key))
            .then((data: EditViewData) => {
                if(data.id) {
                    this.editView = data.id;
                }
            });
    }

    private getEditKey()
    {
        return 'autocomplete_loader_' + this.application.getView().getId();
    }

    public insert(element: HTMLElement): void
    {
        let config = new AutoCompleteConfig();
        config.create = (type: AutoCompleteType, url: string) => {
            this.currentType = type;
            if(this.editView != null) {
                this.application.getEventDispatcher().dispatch(new CloseEvent(this.editView))
                    .then(() => {
                        this.openView(url);
                    })
                    .catch(() => {})
                ;
            } else {
                this.openView(url);
            }
        };

        let elements = this.findElements(element, '[data-auto-complete-entity]');
        for(element of elements) {
            new AutoCompleteType(element, config);
        }
    }

    public openView(url: string)
    {
        this.application.getEventDispatcher()
            .dispatch(new CreateEvent({
                label: this.application.getTranslator().trans('enhavo_app.create'),
                component: 'iframe-view',
                url: url
            })).then((view: ViewInterface) => {
            this.editView = view.id;
        })
    }
}

interface EditViewData {
    id: number;
}