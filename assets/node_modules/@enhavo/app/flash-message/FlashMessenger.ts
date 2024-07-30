
export class FlashMessenger
{
    public messages: FlashMessage[] = [];

    constructor()
    {
        setInterval(() => {
            this.tick();
        }, 1000);
    }

    public add(message: string = null, type: string = FlashMessage.SUCCESS)
    {
        this.addMessage(new FlashMessage(message, type))
    }

    public addMessage(message: FlashMessage)
    {
        this.messages.push(message);
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

    protected tick()
    {
        for (let message of this.messages) {
            message.ttl--
        }

        for (let message of this.messages) {
            if (message.ttl <= 0) {
                this.messages.splice(this.messages.indexOf(message), 1);
            }
        }
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
