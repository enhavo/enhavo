import EventDispatcher from './EventDispatcher';
import ViewInterface from './ViewInterface';
import ViewRegistry from './ViewRegistry';
import CreateEvent from "./Event/CreateEvent";
import LoadedEvent from "./Event/LoadedEvent";
import CloseEvent from "./Event/CloseEvent";
import RemoveEvent from "./Event/RemoveEvent";
import ArrangeEvent from "./Event/ArrangeEvent";
import dispatcher from "./dispatcher";
import registry from "./registry";
import ViewStackData from "./ViewStackData";
import RemovedEvent from "./Event/RemovedEvent";
import ClearEvent from "./Event/ClearEvent";
import ClearedEvent from "./Event/ClearedEvent";
import ArrangeManager from "./ArrangeManager";
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

    constructor(data: ViewStackData)
    {
        this.dispatcher = dispatcher;
        this.registry = registry;

        for(let i in data.views) {
            let view = registry.getFactory(data.views[i].component).createFromData(data.views[i]);
            view.id = this.nextId++;
            _.extend(data.views[i], view);
        }
        this.views = <ViewInterface[]>data.views;
        this.data = data;

        this.arrangeManager = new ArrangeManager(this.views, data);

        this.addCloseListener();
        this.addRemoveListener();
        this.addLoadedListener();
        this.addOpenListener();
        this.addClearListener();
        this.addArrangeListener();
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
    
    private addCloseListener()
    {
        this.dispatcher.on('close', (event: CloseEvent) => {
            let view = this.get(event.id);
            if(view) {
                this.close(view);
            }
        });
    }
    
    private addOpenListener()
    {
        this.dispatcher.on('create', (event: CreateEvent) => {
            let view = this.registry.getFactory(event.data.component).createFromData(event.data);
            view.id = this.generateId();
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
        this.views.push(view);
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