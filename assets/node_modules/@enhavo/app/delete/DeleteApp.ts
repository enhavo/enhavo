import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import View from "@enhavo/app/view/View";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";
import {FlashMessenger} from "@enhavo/app/flash-message/FlashMessenger";

export default class DeleteApp
{
    public data: any;
    private readonly eventDispatcher: FrameEventDispatcher;
    private readonly view: View;
    private readonly flashMessenger: FlashMessenger;
    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(
        data: any,
        eventDispatcher: FrameEventDispatcher,
        view: View,
        flashMessenger: FlashMessenger,
        componentRegistry: ComponentRegistryInterface
    ) {
        this.data = data;
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.flashMessenger = flashMessenger;
        this.componentRegistry = componentRegistry;
    }

    init() {
        this.componentRegistry.registerStore('deleteApp', this);
        this.data = this.componentRegistry.registerData(this.data);

        this.view.init();
        this.view.addDefaultCloseListener();

        this.eventDispatcher.dispatch(new UpdatedEvent(this.view.getId()));

        this.view.ready();
    }

    close() {
        this.eventDispatcher.dispatch(new CloseEvent(this.view.getId()));
    }
}
