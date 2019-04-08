import * as $ from 'jquery';
import * as _ from "lodash";
import Tab from "@enhavo/app/Form/Tab";
import FormData from "@enhavo/app/Form/FormData";
import FormRegistry from "@enhavo/form/FormRegistry";
import FlashMessenger from "@enhavo/app/FlashMessage/FlashMessenger";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import UpdatedEvent from "@enhavo/app/ViewStack/Event/UpdatedEvent";
import View from "@enhavo/app/ViewStack/View";

export default class Form
{
    private tabs: Tab[];
    private data: FormData;
    private registry: FormRegistry;
    private flashMessenger: FlashMessenger;
    private eventDispatcher: EventDispatcher;

    constructor(
        data: FormData,
        registry: FormRegistry,
        flashMessenger: FlashMessenger,
        eventDispatcher: EventDispatcher,
        view: View
    ) {
        this.data = _.extend(data, new FormData);
        for (let i in data.tabs) {
            _.extend(data.tabs[i], new Tab);
        }
        this.tabs = data.tabs;
        this.registry = registry;
        this.flashMessenger = flashMessenger;
        this.eventDispatcher = eventDispatcher;

        if(this.flashMessenger.has('success')) {
            this.eventDispatcher.dispatch(new UpdatedEvent(view.getId()))
        }
    }

    public load()
    {
        for(let tab of this.tabs) {
            if(!this.data.tab) {
                this.data.tab = tab.key;
            }
            tab.element = <HTMLElement>$('[data-tab-container]').find('[data-tab='+tab.key+']')[0];
        }
    }

    public changeTab(key: string)
    {
        this.data.tab = key;
    }

    public changeForm()
    {
        this.data.formChanged = true;
    }
}