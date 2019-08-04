import Message from "@enhavo/app/FlashMessage/Message";
import * as _ from "lodash";

export default class FlashMessenger
{
    public messages: Message[];

    constructor(messages: Message[])
    {
        this.messages = messages;
        for (let i in messages) {
            _.extend(messages[i], new Message());
        }
        setInterval(() => {
            this.tick();
        }, 1000)
    }

    public addMessage(message: Message)
    {
        this.messages.push(message);
    }

    public has(type: string)
    {
        for(let message of this.messages) {
            if(message.type == type) {
                return true;
            }
        }
        return false;
    }

    protected tick()
    {
        for(let message of this.messages) {
            message.ttl--
        }

        for(let message of this.messages) {
            if(message.ttl <= 0) {
                this.messages.splice(this.messages.indexOf(message), 1);
            }
        }
    }
}