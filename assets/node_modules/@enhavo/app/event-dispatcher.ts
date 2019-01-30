import { EventDispatcher } from './Event/EventDispatcher'

declare global {
    interface Window { eventDispatcher: EventDispatcher; }
}

if(!window.eventDispatcher) {
    window.eventDispatcher = new EventDispatcher();
}

export default window.eventDispatcher;