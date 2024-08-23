import {Event} from "@enhavo/app/frame/EventDispatcher"
import {EventDispatcher} from "./EventDispatcher";
import {FrameStack} from "./FrameStack";
import {Frame} from "./Frame";
import {b} from "vite/dist/node/types.d-aGj9QkWt";

/**
 * @internal
 */
export class FrameStackSubscriber
{
    constructor(
        private readonly dispatcher: EventDispatcher,
        private readonly frameStack: FrameStack,
    ) {
    }

    public subscribe()
    {
        this.addFrameAddListener();
        this.addFrameGetListener();
        this.addFrameLoadedListener();
        this.addFrameUpdateListener();
        this.addFrameClearListener();
        this.addFrameRemoveListener();
    }

    private addFrameAddListener()
    {
        this.dispatcher.on('frame_add', (event: Event) => {
            this.frameStack.addFrame((event as FrameAdd).options);
        });
    }

    private addFrameGetListener()
    {
        this.dispatcher.on('frame_get', (event: Event) => {
            event.resolve(this.frameStack.getFrames());
        });
    }

    private addFrameLoadedListener()
    {
        this.dispatcher.on('frame_loaded', (event: Event) => {
            const frame = this.frameStack.getFrame((event as FrameLoaded).id);
            if (frame) {
                frame.loaded = true;
            }
        });
    }

    private addFrameUpdateListener()
    {
        this.dispatcher.on('frame_update', (event: Event) => {
            const frame = this.frameStack.getFrame((event as FrameUpdate).frame.id);
            Object.assign(frame, (event as FrameUpdate).frame);
        });
    }

    private addFrameClearListener()
    {
        this.dispatcher.on('frame_clear', async (event: Event) => {
            const success = await this.frameStack.clearFrames();
            event.resolve(success);
        });
    }

    private addFrameRemoveListener()
    {
        this.dispatcher.on('frame_remove', async (event: Event) => {
            const success = await this.frameStack.removeFrame((event as FrameRemove).frame);
            event.resolve(success);
        });
    }
}

export class FrameLoaded extends Event
{
    constructor(
        public id: string,
    )
    {
        super('frame_loaded');
    }
}

export class FrameAdd extends Event
{
    constructor(
        public options: object
    )
    {
        super('frame_add');
    }
}

export class FrameGet extends Event
{
    constructor()
    {
        super('frame_get');
    }
}

export class FrameUpdate extends Event
{
    constructor(
        public frame: Frame
    )
    {
        super('frame_update');
    }
}

export class FrameRemove extends Event
{
    constructor(
        public frame: Frame,
        public force: boolean = false,
    )
    {
        super('frame_remove');
    }
}

export class FrameClear extends Event
{
    constructor()
    {
        super('frame_clear');
    }
}
