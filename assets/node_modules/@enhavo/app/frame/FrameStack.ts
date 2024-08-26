import {Event, FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import {Frame} from '@enhavo/app/frame/Frame';
import generateId from "uuid/v4";


export class FrameStack
{
    private frames: Frame[] = [];

    constructor(
        private eventDispatcher: FrameEventDispatcher
    ) {
    }

    public async setFrames(options: object[])
    {
        for (let frame of this.frames) {
            await this.removeFrame(frame, true);
        }

        for (let frameOptions of options) {
            await this.addFrame(frameOptions);
        }
    }

    public async updateFrames(frames: Frame[]|object[])
    {
        for (let updateFrame of frames) {
            let found = false;
            for (let frame of this.frames) {
                if ((updateFrame as Frame).id == frame.id) {
                    Object.assign(frame, updateFrame);
                    found = true;
                    break;
                }
            }
            if (found === false) {
                await this.addFrame(updateFrame);
            }
        }

        for (let frame of this.frames) {
            let found = false;
            for (let updateFrame of frames) {
                if ((updateFrame as Frame).id == frame.id) {
                    found = true;
                    break;
                }
            }
            if (found === false) {
                await this.removeFrame(frame);
            }
        }
    }

    public async addFrame(options: object)
    {
        if (options['key'] !== undefined && options['parent'] !== undefined) {
            for (let frame of this.frames) {
                if (frame.parent == options['parent'] && frame.key == options['key']) {
                    await this.removeFrame(frame);
                    //options.width = frame.width;
                    break;
                }
            }
        }

        let frame = this.createFrame(options);

        if (options['wait'] === false) {
            frame.loaded = true;
        }

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

    public async clearFrames(force: boolean = false): Promise<boolean>
    {
        for (let frame of this.frames) {
             let success = await this.removeFrame(frame, force);
             if (success === false) {
                 return false;
             }
        }
        return true;
    }

    public getFrames(parentId: string = undefined): Frame[]
    {
        let frames = [];
        for (let frame of this.frames) {
            if (!frame.removed) {
                if ((parentId != undefined && frame.parent == parentId) || parentId == undefined) {
                    frames.push(frame);
                }
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
         * We can't delete frame with splice, because the vue components will unmount and mount again because we
         * provide a key in v-for and this will reload the iframe, to fix this issue, the best practise is to delete only
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
