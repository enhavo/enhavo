import { EventDispatcher, ComponentAwareInterface} from '@enhavo/core';
import ViewInterface from './ViewInterface';
import ViewRegistry from './ViewRegistry';
import CreateEvent from "./Event/CreateEvent";
import LoadedEvent from "./Event/LoadedEvent";
import CloseEvent from "./Event/CloseEvent";
import dispatcher from "./dispatcher";
import registry from "./registry";
import * as _ from 'lodash';

export default class ViewStack
{
    private readonly views: ViewInterface[];
    private readonly dispatcher: EventDispatcher;
    private readonly registry: ViewRegistry;
    private nextId: number = 1;

    constructor(data: ComponentAwareInterface[])
    {
        this.dispatcher = dispatcher;
        this.registry = registry;

        for(let i in data) {
            let view = registry.getFactory(data[i].component).createFromData(data[i]);
            view.id = this.nextId++;
            _.extend(data[i], view);
        }
        this.views = <ViewInterface[]>data;
        this.arrange();

        this.addCloseListener();
        this.addLoadedListener();
        this.addOpenListener();
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
        this.remove(view);
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