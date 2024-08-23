import {Event, EventDispatcher} from "@enhavo/app/frame/EventDispatcher";
import {Frame} from '@enhavo/app/frame/Frame';
import generateId from "uuid/v4";
import {FrameUpdate} from "./FrameStackSubscriber";

/**
 * @internal
 */
export class FrameStack
{
    private frames: Frame[] = [];
    public width: number = null;

    constructor(
        private eventDispatcher: EventDispatcher
    ) {
    }

    private isRootWindow(): boolean
    {
        return window.name == '';
    }

    public setFrames(options: object[])
    {
        for (let frame of this.frames) {
            this.removeFrame(frame, true);
        }

        for (let frameOptions of options) {
            this.addFrame(options);
        }
    }

    public addFrame(options: object)
    {
        let frame = this.createFrame(options);
        this.frames.push(frame);
        this.eventDispatcher.dispatch(new FrameAdded(frame));
    }

    private createFrame(options: object): Frame
    {
        const frame = new Frame();
        Object.assign(frame, options);

        if (!frame.id) {
            frame.id = this.generateId();
        }

        const self = this;

        return new Proxy(frame, {
            set(target, prop, value) {
                target[prop] = value;
                self.eventDispatcher.dispatch(new FrameUpdated(frame));
                return true;
            },
        });
    }

    public async clearFrames(): Promise<boolean>
    {
        for (let frame of this.frames) {
             let success = await this.removeFrame(frame);
             if (success === false) {
                 return false;
             }
        }
        return true;
    }

    public getFrames(): Frame[]
    {
        let frames = [];
        for (let frame of this.frames) {
            if(!frame.removed) {
                frames.push(frame);
            }
        }
        return frames;
    }

    public getFrame(id: string): Frame
    {
        for (let frame of this.getFrames()) {
            if(frame.id == id) {
                return frame;
            }
        }
        return null;
    }

    public async removeFrame(frame: Frame, force = false): Promise<boolean>
    {
        let success: boolean = true;

        if (frame.keepAlive) {
            frame.display = false;
        } else if (frame.closeable || force) {
            frame.removed = true;
        } else if (!frame.closeable) {
            success = await this.eventDispatcher.request(new FrameRemoveRequest(frame)) as boolean;
            if (success) {
                frame.removed = true
            } else {
                success = false;
            }
        }

        /**
         * We can't delete windows by splice, because the vue components will unmount and mount again because we use
         * provide key in v-for and this will reload the iframe, to fix this issue, the best practise is to delete only
         * the last element. Other elements will just hide until it is the last of the array.
         */
        while (this.frames.length > 0 && this.frames[this.frames.length-1].removed) {
            this.frames.pop();
        }

        this.eventDispatcher.dispatch(new FrameRemoved(frame));

        return success;
    }

    public getRenderFrames()
    {
        return this.frames;
    }

    private generateId(): string
    {
        return generateId();
    }
}

export class FrameRemoveRequest extends Event
{
    constructor(
        public frame: Frame
    )
    {
        super('frame_remove_request');
    }
}

export class FrameAdded extends Event
{
    constructor(
        public frame: Frame
    )
    {
        super('frame_added');
    }
}

export class FrameRemoved extends Event
{
    constructor(
        public frame: Frame
    )
    {
        super('frame_removed');
    }
}

export class FrameUpdated extends Event
{
    constructor(
        public frame: Frame
    )
    {
        super('frame_updated');
    }
}
