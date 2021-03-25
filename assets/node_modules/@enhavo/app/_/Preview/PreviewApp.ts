import ActionManager from "@enhavo/app/Action/ActionManager";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import DataEvent from "@enhavo/app/ViewStack/Event/DataEvent";
import * as $ from "jquery"
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";
import PreviewData from "@enhavo/app/Preview/PreviewData";

export default class PreviewApp
{
    public data: PreviewData;

    private readonly actionManager: ActionManager;
    private readonly eventDispatcher: EventDispatcher;
    private readonly view: View;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        data: PreviewData,
        eventDispatcher: EventDispatcher,
        view: View,
        actionManager: ActionManager,
        componentRegistry: ComponentRegistryInterface
    ) {
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.actionManager = actionManager;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.actionManager.init();
        this.view.init();

        this.componentRegistry.registerData(this.data);
        this.componentRegistry.registerStore('previewApp', this);

        this.eventDispatcher.on('data', (event: DataEvent) => {
            if(event.id == this.view.getId()) {
                if (!event.data || event.data.length === 0) {
                    return;
                }
                this.data.inputs = event.data;
                // delay submit so vue has time to update form
                setTimeout(() => {
                    this.submit();
                }, 500);
            }
        });

        this.view.addDefaultCloseListener();
        this.view.ready();
    }

    submit()
    {
        $('form').submit();
    }
}
