import {FrameEventDispatcher} from "@enhavo/app/frame/FrameEventDispatcher";
import {
    FrameAdd,
    FrameClear,
    FrameGet,
    FrameLoaded,
    FrameRemove,
    FrameUpdate,
    FrameSave,
    FrameArrange,
} from "@enhavo/app/frame/FrameStackSubscriber";
import {Frame} from "@enhavo/app/frame/Frame";
import {FrameAdded, FrameUpdated} from "@enhavo/app/frame/FrameStack";
import {Event, Subscriber} from "@enhavo/app/frame/FrameEventDispatcher";
import generateId from "uuid/v4";

export class FrameManager
{
    private mainFrame: boolean = false;
    private frames: Frame[] = null;
    private sendUpdates: boolean = true;
    private waitPromise: Promise<void> = null;

    constructor(
        private eventDispatcher: FrameEventDispatcher
    ) {
        window.addEventListener('click', () => {
             this.eventDispatcher.dispatch(new Event('click'));
        });
    }

    private init(): Promise<void>
    {
        if (window.name === '' && this.frames === null && !this.mainFrame) {
            this.frames = [new Frame({id: ""})];
        }

        // if init is called one after the other, the fetch is may not be finish, so we have to sync the fetches
        return new Promise((resolve) => {
            if (this.frames !== null) {
                resolve();
            } else if (this.waitPromise !== null) {
                this.waitPromise.then(() => {
                    resolve()
                });
            } else {
                this.waitPromise = new Promise((resolveWait) => {
                    this.subscribe();
                    this.eventDispatcher.request(new FrameGet()).then((frames) => {
                        this.frames = [];
                        for (let options of (frames as object[])) {
                            this.frames.push(this.createFrame(options));
                        }
                        this.waitPromise = null;
                        resolveWait();
                        resolve();
                    });
                });
            }
        });
    }

    private subscribe()
    {
        this.eventDispatcher.on('frame_updated', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame !== null) {
                this.sendUpdates = false;
                Object.assign(frame, (event as FrameAdded).frame);
                this.sendUpdates = true;
            }
        }, 100);

        this.eventDispatcher.on('frame_removed', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame !== null) {
                this.frames.splice(this.frames.indexOf(frame), 1);
            }
        }, 100);

        this.eventDispatcher.on('frame_added', async (event) => {
            let frame = await this.getFrame((event as FrameAdded).frame.id);
            if (frame === null) {
                this.frames.push(this.createFrame((event as FrameAdded).frame));
            }
        }, 100);
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

    public getId(): string
    {
        return window.name;
    }

    public loaded()
    {
        this.eventDispatcher.dispatch(new FrameLoaded(window.name));
    }

    public async addFrame(options: object): Promise<Frame>
    {
        return await this.eventDispatcher.request(new FrameAdd(options)) as Frame;
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

    public on(eventName: string, callback: (event: Event) => void, priority: number = 10): Subscriber
    {
        return this.eventDispatcher.on(eventName, callback, priority);
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

    public save(): void
    {
        this.eventDispatcher.dispatch(new FrameSave());
    }

    public arrange(): void
    {
        this.eventDispatcher.dispatch(new FrameArrange());
    }

    /**
     * Open a frame and save and arrange the frame stack. When the new frame has a wait = true option,
     * then openFrame waits until the frame send a loaded message, else it will just return after the frame
     * was added.
     */
    public async openFrame(options: object): Promise<Frame>
    {
        let frameOptions = new Frame(options);

        return new Promise(async (resolve) => {
            if (frameOptions.wait) {
                this.on('frame_loaded', async (event) => {
                    if ((event as FrameLoaded).id === frameOptions.id) {
                        let frame = await this.getFrame(frameOptions.id)
                        resolve(frame);
                    }
                });
            }

            let frame = await this.addFrame(frameOptions.getOptions());
            this.save();
            this.arrange()

            if (!frameOptions.wait) {
                resolve(frame);
            }
        });
    }

    public onChange(callback: (frame: Frame, type: string) => void): Subscriber
    {
        return this.eventDispatcher.on('frame_updated', async (event) => {
            if (window.name === (event as FrameUpdated).frame.id) {
                let frame = await this.getFrame();
                if (frame !== null) {
                    callback(frame, 'updated');
                }
            }
        }, 90);
    }

    public wait(frame: Frame): Promise<void>
    {
        return new Promise(resolve => {
            let resolved = false;
            this.on('frame_loaded', async (event) => {
                if ((event as FrameLoaded).id === frame.id && !resolved) {
                    resolve();
                }
            });

            if (frame.loaded) {
                resolve();
                resolved = true;
            }
        })
    }

    public setMainFrame(value: boolean)
    {
        this.mainFrame = value;
    }

    public isMainFrame(): boolean
    {
        return this.mainFrame;
    }

    public isRoot(): boolean
    {
        return this.getId() === '';
    }
}
