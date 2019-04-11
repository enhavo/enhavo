import DataLoader from '@enhavo/app/DataLoader';
import ActionManager from "@enhavo/app/Action/ActionManager";
import AppInterface from "@enhavo/app/AppInterface";
import AbstractViewApp from "@enhavo/app/AbstractViewApp";
import EventDispatcher from "@enhavo/app/ViewStack/EventDispatcher";
import View from "@enhavo/app/View/View";
import CloseEvent from "@enhavo/app/ViewStack/Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack/Event/RemoveEvent";
import Confirm from "@enhavo/app/View/Confirm";
import FormatData from "@enhavo/media/ImageCropper/FormatData";
import * as _ from "lodash";

export default class ImageCropperApp extends AbstractViewApp implements AppInterface
{
    private actionManager: ActionManager;
    protected data: FormatData;

    constructor(loader: DataLoader, eventDispatcher: EventDispatcher, view: View, actionManager: ActionManager)
    {
        super(loader, eventDispatcher, view);
        loader.load().format = _.extend(loader.load().format, new FormatData);
        this.actionManager = actionManager;
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
                    this.eventDispatcher.dispatch(new RemoveEvent(id));
                }
            }
        });
    }
}