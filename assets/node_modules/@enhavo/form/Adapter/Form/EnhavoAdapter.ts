import { FormListener } from "app/Form/Form";
import { FormInsertEvent } from "app/Form/Form";
import { DatePicker } from "app/Form/Form";

class EnhavoAdapter
{
    constructor()
    {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        FormListener.onInsert(function(event: FormInsertEvent) {
            DatePicker.apply(event.getElement());
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;