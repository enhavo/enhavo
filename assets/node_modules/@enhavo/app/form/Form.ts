import * as _ from "lodash";
import Tab from "@enhavo/app/form/Tab";
import FormData from "@enhavo/app/form/FormData";
import FormRegistry from "@enhavo/app/form/FormRegistry";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import UpdatedEvent from "@enhavo/app/view-stack/event/UpdatedEvent";
import View from "@enhavo/app/view/View";
import SaveStateEvent from "@enhavo/app/view-stack/event/SaveStateEvent";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class Form
{
    public data: FormData;
    public tabs: Tab[];

    private readonly view: View;
    private readonly registry: FormRegistry;
    private readonly flashMessenger: FlashMessenger;
    private readonly eventDispatcher: EventDispatcher;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        data: FormData,
        registry: FormRegistry,
        flashMessenger: FlashMessenger,
        eventDispatcher: EventDispatcher,
        view: View,
        componentRegistry: ComponentRegistryInterface,
    ) {
        this.view = view;
        this.data = _.extend(data, new FormData);
        this.registry = registry;
        this.flashMessenger = flashMessenger;
        this.eventDispatcher = eventDispatcher;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.flashMessenger.init();
        this.view.init();

        if(this.flashMessenger.has('success')) {
            this.eventDispatcher.dispatch(new UpdatedEvent(this.view.getId(), this.data.resource))
        }

        for (let i in this.data.tabs) {
            this.data.tabs[i] = _.assign(new Tab, this.data.tabs[i]);
        }

        this.tabs = this.data.tabs;

        this.data = this.componentRegistry.registerData(this.data);
        this.componentRegistry.registerStore('form', this);
        this.tabs = this.componentRegistry.registerData(this.tabs);
    }

    public load()
    {
        this.changeTab(this.tabs[0].key, false);

        this.view.loadValue('active-tab', value => {
            if (value) {
                this.changeTab(value);
            } else {
                this.changeTab(this.tabs[0].key);
            }
        });
    }

    public changeTab(key: string, store: boolean = true)
    {
        for(let tab of this.tabs) {
            if (tab.key === key) {
                tab.active = true;
                continue;
            }
            tab.active = false;
        }

        if (store) {
            // store active tab
            this.view.storeValue('active-tab', key, () => {
                this.eventDispatcher.dispatch(new SaveStateEvent());
            });
        }
    }

    public changeForm()
    {
        this.data.formChanged = true;
    }

    public isFormChanged(): boolean
    {
        return this.data.formChanged;
    }

    public hasTabs(): boolean
    {
        return this.tabs.length > 1
    }
}
