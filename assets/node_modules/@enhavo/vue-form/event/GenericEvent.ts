import {EventInterface} from "@enhavo/vue-form/event/EventInterface";
import {Form} from "@enhavo/vue-form/model/Form";

export class GenericEvent implements EventInterface
{
    constructor(
        public form: Form,
    ) {
    }
}