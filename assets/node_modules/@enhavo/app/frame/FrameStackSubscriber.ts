import {Event} from "@enhavo/app/frame/FrameEventDispatcher"
import {FrameEventDispatcher} from "./FrameEventDispatcher";
import {FrameStack} from "./FrameStack";
import {Frame} from "./Frame";
import {FrameStateManager} from "./FrameStateManager";
import {FrameArrangeManager} from "./FrameArrangeManager";

/**
 * @internal
 */
export class FrameStackSubscriber
{
    constructor(
        private readonly dispatcher: FrameEventDispatcher,
        private readonly frameStack: FrameStack,
        private readonly frameStateManager: FrameStateManager,
        private readonly frameArrangeManager: FrameArrangeManager,
    ) {
    }

    public subscribe()
    {
        if (window.name == "") {
            this.addFrameAddListener();
            this.addFrameGetListener();
            this.addFrameLoadedListener();
            this.addFrameUnloadListener();
            this.addFrameLabelListener();
            this.addFrameUpdateListener();
            this.addFrameClearListener();
            this.addFrameRemoveListener();
            this.addFrameCloseListener();
            this.addFrameSaveListener();
            this.addFrameArrangeListener();
            this.addFrameClickListener();
        }
    }

    private addFrameAddListener()
    {
        this.dispatcher.on('frame_add', async (event: Event) => {
            const options = (event as FrameAdd).options;
            if (options['parent'] === undefined) {
                options['parent'] = event.origin;
            }
            let frame = await this.frameStack.addFrame(options);
            event.resolve(frame);
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

    private addFrameUnloadListener()
    {
        this.dispatcher.on('frame_unload', (event: Event) => {
            const frame = this.frameStack.getFrame((event as FrameLoaded).id);

            if (frame) {
                frame.loaded = false;
            }

            event.resolve();
        });
    }

    private addFrameLabelListener()
    {
        this.dispatcher.on('frame_label', (event: Event) => {
            const frame = this.frameStack.getFrame((event as FrameLabel).id);

            if (frame) {
                frame.label = (event as FrameLabel).label;
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
            const frame = this.frameStack.getFrame((event as FrameRemove).frame.id);
            const success = await this.frameStack.removeFrame(frame, (event as FrameRemove).force);
            event.resolve(success);
        });
    }

    private addFrameCloseListener()
    {
        this.dispatcher.on('frame_close', async (event: Event) => {
            const frame = this.frameStack.getFrame((event as FrameClose).id);
            const success = await this.frameStack.removeFrame(frame, (event as FrameRemove).force);
            if (success) {
                // The frame itself can't trigger save and arrange because it's already removed at this point
                this.frameStateManager.saveState();
                this.frameArrangeManager.arrange();
            }
            event.resolve(success);
        });
    }

    private addFrameSaveListener()
    {
        this.dispatcher.on('frame_save', async (event: Event) => {
            this.frameStateManager.saveState();
            event.resolve();
        });
    }

    private addFrameArrangeListener()
    {
        this.dispatcher.on('frame_arrange', async (event: Event) => {
            this.frameArrangeManager.arrange();
            event.resolve();
        });
    }

    private addFrameClickListener()
    {
        this.dispatcher.on('click', async (event: Event) => {
            if (event.origin) {
                for (let frame of this.frameStack.getFrames()) {
                    frame.focus = frame.id === event.origin;
                }
            }
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

export class FrameUnload extends Event
{
    constructor(
        public id: string,
    )
    {
        super('frame_unload');
    }
}

export class FrameLabel extends Event
{
    constructor(
        public id: string,
        public label: string,
    )
    {
        super('frame_label');
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

export class FrameClose extends Event
{
    constructor(
        public id: string,
        public force: boolean = false,
    )
    {
        super('frame_close');
    }
}

export class FrameClear extends Event
{
    constructor()
    {
        super('frame_clear');
    }
}

export class FrameSave extends Event
{
    constructor()
    {
        super('frame_save');
    }
}

export class FrameArrange extends Event
{
    constructor()
    {
        super('frame_arrange');
    }
}
