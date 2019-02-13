import { EventDispatcher } from '@enhavo/core';
import ViewInterface from './ViewInterface';
import ViewRegistry from './ViewRegistry';
import CreateEvent from "./Event/CreateEvent";
import LoadedEvent from "./Event/LoadedEvent";
import CloseEvent from "./Event/CloseEvent";
import RemoveEvent from "./Event/RemoveEvent";
import dispatcher from "./dispatcher";
import registry from "./registry";
import ViewStackData from "./ViewStackData";
import * as _ from 'lodash';
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
export default class ViewStack
{
    private readonly views: ViewInterface[];
    private readonly dispatcher: EventDispatcher;
    private readonly registry: ViewRegistry;
    private nextId: number = 1;

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
        this.arrange();

        this.addCloseListener();
        this.addRemoveListener();
        this.addLoadedListener();
        this.addOpenListener();
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

    private remove(view: ViewInterface) {
        for(let i in this.views) {
            if(view.id == this.views[i].id) {
                this.views.splice(parseInt(i), 1);
                this.dispatcher.dispatch(new RemovedEvent(view.id));
                return;
            }
        }
        this.arrange();
    }

    private arrange()
    {
        for(let view of this.views) {
            view.width = 200;
        }
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
        this.arrange();
    }

    close(view: ViewInterface)
    {
        if(!view.loaded) {
            this.remove(view);
        }
    }

    getDispatcher()
    {
        return this.dispatcher;
    }

    clear() {
        for(let view of this.views) {
            this.close(view);
        }
    }
}