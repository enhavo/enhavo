import {FrameManager} from "@enhavo/app/frame/FrameManager";
import {Event} from "@enhavo/app/frame/FrameEventDispatcher";

export class FlashMessenger
{
    public messages: FlashMessage[] = [];
    private intervalId: number;

    constructor(
        private frameManager: FrameManager,
    )
    {
    }

    private init()
    {
        if (this.intervalId) {
            return;
        }

        this.intervalId = setInterval(() => {
            this.tick();
        }, 1000);
    }

    private tick()
    {
        for (let message of this.messages) {
            message.ttl--
        }

        for (let message of this.messages) {
            if (message.ttl <= 0) {
                this.deleteMessage(message)
            }
        }
    }

    public add(message: string = null, type: string = FlashMessage.SUCCESS)
    {
        this.addMessage(new FlashMessage(message, type))
    }

    public success(message: string = null)
    {
        this.addMessage(new FlashMessage(message, FlashMessage.SUCCESS))
    }

    public error(message: string = null)
    {
        this.addMessage(new FlashMessage(message, FlashMessage.ERROR))
    }

    public notice(message: string = null)
    {
        this.addMessage(new FlashMessage(message, FlashMessage.NOTICE))
    }

    public warning(message: string = null)
    {
        this.addMessage(new FlashMessage(message, FlashMessage.WARNING))
    }

    public addMessage(message: FlashMessage)
    {
        if (this.frameManager.isRoot()) {
            this.init();
            this.messages.push(message);
        } else {
            this.frameManager.dispatch(new FlashMessageEvent(message.message, message.type));
        }
    }

    public deleteMessage(message: FlashMessage)
    {
        this.messages.splice(this.messages.indexOf(message), 1);
    }

    public has(type: string)
    {
        for (let message of this.messages) {
            if (message.type == type) {
                return true;
            }
        }
        return false;
    }

    public subscribe()
    {
        this.frameManager.on('flash-message', (event) => {
            this.add((event as FlashMessageEvent).message, (event as FlashMessageEvent).type)
        });
    }
}

export class FlashMessage
{
    static SUCCESS = 'success';
    static ERROR = 'error';
    static NOTICE = 'notice';
    static WARNING = 'warning';

    public message: string;
    public type: string;
    public ttl: number = 5;

    constructor(message: string, type: string = FlashMessage.SUCCESS)
    {
        if (type) {
            this.type = type;
        }
        if (message) {
            this.message = message;
        }
    }
}

class FlashMessageEvent extends Event
{
    constructor(
        public message: string,
        public type: string,
    ) {
        super('flash-message');
    }
}
