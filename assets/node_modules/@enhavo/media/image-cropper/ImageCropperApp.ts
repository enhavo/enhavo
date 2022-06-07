import ActionManager from "@enhavo/app/action/ActionManager";
import EventDispatcher from "@enhavo/app/view-stack/EventDispatcher";
import View from "@enhavo/app/view/View";
import CloseEvent from "@enhavo/app/view-stack/event/CloseEvent";
import RemoveEvent from "@enhavo/app/view-stack/event/RemoveEvent";
import Confirm from "@enhavo/app/view/Confirm";
import FormatData from "@enhavo/media/image-cropper/FormatData";
import FlashMessenger from "@enhavo/app/flash-message/FlashMessenger";
import UpdatedEvent from "@enhavo/app/view-stack/event/UpdatedEvent";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";
import * as _ from "lodash";

export default class ImageCropperApp
{
    public data: FormatData;

    private eventDispatcher: EventDispatcher;
    private view: View;
    private actionManager: ActionManager;
    private flashMessenger: FlashMessenger;
    private componentRegistry: ComponentRegistryInterface;

    constructor(data: FormatData, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager, flashMessenger: FlashMessenger, componentRegistry: ComponentRegistryInterface)
    {
        this.data = _.assign(new FormatData(), data);
        this.eventDispatcher = eventDispatcher;
        this.view = view;
        this.actionManager = actionManager;
        this.flashMessenger = flashMessenger;
        this.componentRegistry = componentRegistry;
    }

    public init()
    {
        if(this.flashMessenger.has('success')) {
            this.eventDispatcher.dispatch(new UpdatedEvent(this.view.getId()))
        }

        this.view.init();
        this.actionManager.init();
        this.flashMessenger.init();

        this.componentRegistry.registerStore('imageCropper', this);
        this.data = this.componentRegistry.registerData(this.data);

        this.addCloseListener();
        this.view.ready();
    }

    protected addCloseListener()
    {
        this.eventDispatcher.on('close', (event: CloseEvent) => {
            if(this.view.getId() === event.id) {
                if(this.data.changed) {
                    this.view.confirm(new Confirm(
                        'not saved confirm',
                        () => {
                            event.resolve();
                            let id = this.view.getId();
                            this.eventDispatcher.dispatch(new RemoveEvent(id));
                        },
                        () => {
                            event.reject();
                        }
                    ));
                } else {
                    event.resolve();
                    let id = this.view.getId();
                    this.eventDispatcher.dispatch(new RemoveEvent(id, event.saveState));
                }
            }
        });
    }
}