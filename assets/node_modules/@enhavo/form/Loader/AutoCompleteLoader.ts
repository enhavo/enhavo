import AutoCompleteType from "@enhavo/form/Type/AutoCompleteType";
import AbstractLoader from "@enhavo/form/Loader/AbstractLoader";
import AutoCompleteConfig from "@enhavo/form/Type/AutoCompleteConfig";
import LoadDataEvent from "@enhavo/app/ViewStack/Event/LoadDataEvent";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import DataStorageEntry from "@enhavo/app/ViewStack/DataStorageEntry";
import View from "@enhavo/app/View/View";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import Router from "@enhavo/core/Router";
import FormRegistry from "@enhavo/app/Form/FormRegistry";

export default class AutoCompleteLoader extends AbstractLoader
{
    private currentType: AutoCompleteType;

    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly router: Router;

    constructor(eventDispatcher: EventDispatcher, view: View, router: Router) {
        super();
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.router = router;
        this.initListener();
    }

    private initListener()
    {
        this.eventDispatcher.on('updated', (event: UpdatedEvent) => {
            this.eventDispatcher.dispatch(new LoadDataEvent('autocomplete_loader'))
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
            this.view.open(url, 'autocomplete_loader');
            this.currentType = type;
        };
        config.edit = (type: AutoCompleteType, route: string, id: string) => {
            let url = this.router.generate(route, {id: id});
            this.view.open(url, 'autocomplete_loader');
            this.currentType = type;
        };

        let elements = this.findElements(element, '[data-auto-complete-entity]');
        for(element of elements) {
            FormRegistry.registerType(new AutoCompleteType(element, config));
        }
    }
}
