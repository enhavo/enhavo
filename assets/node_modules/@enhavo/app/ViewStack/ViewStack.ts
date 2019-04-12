import EventDispatcher from '@enhavo/app/ViewStack/EventDispatcher';
import ViewInterface from '@enhavo/app/ViewStack//ViewInterface';
import ViewRegistry from '@enhavo/app/ViewStack//ViewRegistry';
import CreateEvent from "@enhavo/app/ViewStack//Event/CreateEvent";
import LoadedEvent from "@enhavo/app/ViewStack//Event/LoadedEvent";
import CloseEvent from "@enhavo/app/ViewStack//Event/CloseEvent";
import RemoveEvent from "@enhavo/app/ViewStack//Event/RemoveEvent";
import ArrangeEvent from "@enhavo/app/ViewStack//Event/ArrangeEvent";
import ViewStackData from "@enhavo/app/ViewStack//ViewStackData";
import RemovedEvent from "@enhavo/app/ViewStack//Event/RemovedEvent";
import ClearEvent from "@enhavo/app/ViewStack//Event/ClearEvent";
import ClearedEvent from "@enhavo/app/ViewStack//Event/ClearedEvent";
import ArrangeManager from "@enhavo/app/ViewStack/ArrangeManager";
import LoadingEvent from "@enhavo/app/ViewStack/Event/LoadingEvent";
import MenuManager from "@enhavo/app/Menu/MenuManager";
import * as _ from 'lodash';
import * as async from 'async';

export default class ViewStack
{
    private readonly views: ViewInterface[];
    private readonly dispatcher: EventDispatcher;
    private readonly registry: ViewRegistry;
    private data: ViewStackData;
    private nextId: number = 1;
    private arrangeManager: ArrangeManager;

    constructor(
        data: ViewStackData,
        menuManager: MenuManager,
        dispatcher: EventDispatcher,
        viewRegistry: ViewRegistry
    ) {
        this.dispatcher = dispatcher;
        this.registry = viewRegistry;

        for(let i in data.views) {
            let view = viewRegistry.getFactory(data.views[i].component).createFromData(data.views[i]);
            view.id = this.nextId++;
            _.extend(data.views[i], view);
        }
        this.views = <ViewInterface[]>data.views;
        this.data = data;

        this.arrangeManager = new ArrangeManager(this.views, data, menuManager);

        this.addCloseListener();
        this.addRemoveListener();
        this.addLoadedListener();
        this.addCreateListener();
        this.addClearListener();
        this.addArrangeListener();
        this.addLoadingListener();
        this.addExistsListener();
    }

    private addExistsListener()
    {
        this.dispatcher.on('exists', (event: RemoveEvent) => {
            let view = this.get(event.id);
            event.resolve({
                exists: !!view
            });
        });
    }

    private addRemoveListener()
    {
        this.dispatcher.on('remove', (event: RemoveEvent) => {
            let view = this.get(event.id);
            if(view) {
                this.remove(view);
            }
        });
    }

    private addLoadedListener()
    {
        this.dispatcher.on('loaded', (event: LoadedEvent) => {
            let view = this.get(event.id);
            if(view) {
                view.finish();
            }
        });
    }

    private addLoadingListener()
    {
        this.dispatcher.on('loading', (event: LoadingEvent) => {
            let view = this.get(event.id);
            if(view) {
                view.loaded = false;
            }
        });
    }
    
    private addCloseListener()
    {
        this.dispatcher.on('close', (event: CloseEvent) => {
            let view = this.get(event.id);
            if(view) {
                let closed = this.close(view);
                if(closed) {
                    event.resolve();
                }
            }
        });
    }
    
    private addCreateListener()
    {
        this.dispatcher.on('create', (event: CreateEvent) => {
            let view = this.registry.getFactory(event.data.component).createFromData(event.data);
            view.id = this.generateId();
            event.resolve(view);
            this.push(view);
        });
    }

    private addArrangeListener()
    {
        this.dispatcher.on('arrange', (event: ArrangeEvent) => {
            this.arrange();
        });
    }

    private addClearListener()
    {
        this.dispatcher.on('clear', (event: ClearEvent) => {

            if(this.views.length == 0) {
                event.resolve();
                this.dispatcher.dispatch(new ClearedEvent(event.uuid));
            }

            let parallels = [];
            for(let view of this.views) {
                parallels.push((callback: (err: any) => void) => {
                    if(this.close(view)) {
                        callback(null);
                    } else {
                        this.dispatcher.dispatch(new CloseEvent(view.id))
                            .then(() => {
                                callback(null);
                            })
                            .catch(() => {
                                callback('reject');
                            })
                    }
                });
            }

            async.parallel(parallels, (err) => {
                if(err) {
                    event.reject();
                } else {
                    for(let view of this.views) {
                        this.remove(view);
                    }
                    event.resolve();
                    this.dispatcher.dispatch(new ClearedEvent(event.uuid));
                }
            });
        });
    }

    private remove(view: ViewInterface)
    {
        /**
         * We can't delete views by splice or using Vue.delete, because the view can contain an iframe.
         * And because of reload iframe issues with vue and general in dom trees, it is best practise to delete only
         * the last element. Other elements will just hide until it is the last of the array.
         */
        view.removed = true;
        this.dispatcher.dispatch(new RemovedEvent(view.id));
        while(this.views.length > 0 && this.views[this.views.length-1].removed) {
            this.views.pop();
        }
    }

    private arrange()
    {
        this.arrangeManager.arrange();
    }

    private generateId(): number
    {
        let id = this.nextId;
        this.nextId++;
        return id;
    }
    
    get(id: number) {
        for(let view of this.views) {
            if(view.id == id) {
                return view;
            }
        }
    }

    push(view: ViewInterface)
    {
        /**
         * Delay pushing a new view, so vue can update with a new tick.
         * A new tick is needed, because iframe render only once (v-once) and vue need
         * time to destroy the iframe, otherwise the pushed view keeps the iframe from the
         * popped view before.
         */
        setTimeout(() => {
            this.views.push(view);
        }, 10);
    }

    close(view: ViewInterface): boolean
    {
        if(!view.loaded) {
            this.remove(view);
            return true;
        }
        return false;
    }

    getDispatcher()
    {
        return this.dispatcher;
    }
}