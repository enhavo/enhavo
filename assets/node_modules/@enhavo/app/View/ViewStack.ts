import { View } from './View';
import { EventDispatcher } from '../Event/EventDispatcher';
import { Event } from '../Event/Event';

export class ViewStack
{
    private views: View[];
    private eventDispatcher: EventDispatcher;
    private nextId: number = 1;

    constructor(views: View[], eventDispatcher: EventDispatcher)
    {
        this.views = views;
        this.eventDispatcher = eventDispatcher;
        let self = this;

        eventDispatcher.on('open-view', (event: Event) => {
            console.log('open triggered');
            let view = new View(self.nextId, 'number');
            self.views.push(view);
            self.nextId++;
        });

        eventDispatcher.on('close-view', (event: Event) => {
            console.log('close triggered');
            self.views.pop();
        });
    }

    public push(view: View) {

    }

    public remove(view: View)
    {

    }

    public clear()
    {

    }

    public arrange()
    {

    }
}