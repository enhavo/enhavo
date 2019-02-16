import dispatcher from './dispatcher';
import RemovedEvent from "@enhavo/app/ViewStack/Event/RemovedEvent";
import * as URI from 'urijs';

export class FrameStorage
{
    private frames: Frame[] = [];

    constructor() {
        dispatcher.all((event) => {
            for(let frame of this.frames) {
                if(event.origin != frame.id && frame.element.contentWindow != null) {
                    if(dispatcher.isDebug()) {
                        console.groupCollapsed('pass event ('+event.name+') to iframe ' + frame.id);
                        console.dir(event);
                        console.groupEnd()
                    }
                    let data = 'view_stack_event|'+JSON.stringify(event);
                    frame.element.contentWindow.postMessage(data, '*');
                }
            }
        });

        dispatcher.on('removed', (event: RemovedEvent) => {
            let frame = this.getFrame(event.id);
            let index = this.frames.indexOf(frame);
            this.frames.splice(index, 1);
        });
    }

    create(id: number, url: string)
    {
        let uri = new URI(url);
        let element = <HTMLIFrameElement>document.createElement('iframe');
        element.src = uri.addQuery('view_id', id) + '';
        this.frames.push(new Frame(id, element));
        return element;
    }

    get(id: number): HTMLElement
    {
        let frame = this.getFrame(id);
        if(frame) {
            return frame.element;
        }
        return null;
    }

    has(id: number): boolean
    {
        return this.getFrame(id) != null;
    }

    private getFrame(id: number): Frame|null
    {
        for(let frame of this.frames) {
            if(frame.id == id) {
                return frame;
            }
        }
        return null;
    }
}

class Frame
{
    element: HTMLIFrameElement;
    id: number;

    constructor(id: number, element: HTMLIFrameElement)
    {
        this.element = element;
        this.id = id;
    }
}

const storage = new FrameStorage();
export default storage;