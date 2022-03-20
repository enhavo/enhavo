import Message from "@enhavo/app/flash-message/Message";
import * as _ from "lodash";
import ComponentRegistryInterface from "@enhavo/core/ComponentRegistryInterface";

export default class FlashMessenger
{
    public messages: Message[];

    private readonly componentRegistry: ComponentRegistryInterface;

    constructor(messages: Message[], componentRegistry: ComponentRegistryInterface)
    {
        this.messages = messages;
        this.componentRegistry = componentRegistry;
    }

    init() {
        for (let i in this.messages) {
            _.extend(this.messages[i], new Message());
        }
        setInterval(() => {
            this.tick();
        }, 1000);

        this.componentRegistry.registerStore('flashMessenger', this);
        this.messages = this.componentRegistry.registerData(this.messages);
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
