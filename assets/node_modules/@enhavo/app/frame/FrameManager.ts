import {EventDispatcher} from "@enhavo/app/frame/EventDispatcher";
import {FrameAdd, FrameClear, FrameGet, FrameLoaded, FrameRemove, FrameUpdate} from "@enhavo/app/frame/FrameStackSubscriber";
import {Frame} from "@enhavo/app/frame/Frame";
import {FrameAdded} from "@enhavo/app/frame/FrameStack";
import {Event, Subscriber} from "@enhavo/app/frame/EventDispatcher";

export class FrameManager
{
    private frames: Frame[] = null;
    private sendUpdates: boolean = true;
    private wait: Promise<void> = null;

    constructor(
        private eventDispatcher: EventDispatcher
    ) {
        window.addEventListener('click', () => {
             this.eventDispatcher.dispatch(new Event('click'));
        });

        this.eventDispatcher.on('frame_updated', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame !== null) {
                this.sendUpdates = false;
                Object.assign(frame, (event as FrameAdded).frame);
                this.sendUpdates = true;
            }
        });

        this.eventDispatcher.on('frame_removed', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame !== null) {
                this.frames.splice(this.frames.indexOf(frame), 1);
            }
        });

        this.eventDispatcher.on('frame_added', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame === null) {
                this.frames.push(this.createFrame((event as FrameAdded).frame));
            }
        });
    }

    private init(): Promise<void>
    {
        // if init is called one after the other, the fetch is may not be finish, so we have to sync the fetches
        return new Promise((resolve) => {
            if (this.frames !== null) {
                resolve();
            } else if (this.wait !== null) {
                this.wait.then(() => {
                    resolve()
                });
            } else {
                this.wait = new Promise((resolveWait) => {
                    this.eventDispatcher.request(new FrameGet()).then((frames: object[]) => {
                        this.frames = [];
                        for (let options of frames) {
                            this.frames.push(this.createFrame(options));
                        }
                        this.wait = null;
                        resolveWait();
                        resolve();
                    });
                });
            }
        });
    }

    private createFrame(options: object): Frame
    {
        const frame = new Frame();
        Object.assign(frame, options);

        const self = this;

        return new Proxy(frame, {
            set(target, prop, value) {
                target[prop] = value;
                self.sendUpdateFrame(target)
                return true;
            },
        });
    }

    private sendUpdateFrame(frame: Frame)
    {
        if (!this.sendUpdates) {
            return;
        }

        this.eventDispatcher.dispatch(new FrameUpdate(frame));
    }

    public loaded()
    {
        this.eventDispatcher.dispatch(new FrameLoaded(window.name));
    }

    public async addFrame(options: object): Promise<void>
    {
        await this.eventDispatcher.request(new FrameAdd(options));
    }

    public async getFrames(): Promise<Frame[]>
    {
        await this.init();
        return this.frames;
    }

    public async getFrame(id: string = null): Promise<Frame>
    {
        await this.init();

        if (id === null) {
            id = window.name;
        }

        for (let frame of this.frames) {
            if (frame.id === id) {
                return frame;
            }
        }

        return null;
    }

    public async clearFrames(): Promise<boolean>
    {
        return (await this.eventDispatcher.request(new FrameClear()) as boolean);
    }

    public async removeFrame(frame: Frame, force = false): Promise<boolean>
    {
        return (await this.eventDispatcher.request(new FrameRemove(frame, force)) as boolean);
    }

    public on(eventName: string, callback: (event: Event) => void): Subscriber
    {
        return this.eventDispatcher.on(eventName, callback);
    }

    public removeSubscriber(subscriber: Subscriber)
    {
        this.eventDispatcher.remove(subscriber);
    }

    public request(event: Event): Promise<object|object[]|boolean|string|number>
    {
        return this.eventDispatcher.request(event);
    }

    public dispatch(event: Event): void
    {
        this.eventDispatcher.dispatch(event);
    }
}
