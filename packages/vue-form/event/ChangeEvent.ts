import {Form} from "@enhavo/vue-form/model/Form";
import {GenericEvent} from "@enhavo/vue-form/event/GenericEvent";

export class ChangeEvent extends GenericEvent
{
    constructor(
        form: Form,
        public value: any,
    ) {
        super(form);
    }
}
