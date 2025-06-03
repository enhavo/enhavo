import {EventInterface} from "@enhavo/vue-form/event/EventInterface";
import {Form} from "@enhavo/vue-form/model/Form";

export class Listener
{
    constructor(
        public eventNames: string[],
        public callback: (event: EventInterface) => void,
        public form: Form = null,
    ) {
    }
}