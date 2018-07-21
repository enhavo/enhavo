import { DynamicForm, DynamicFormConfig } from 'app/app/DynamicForm';
import { FormListener } from "app/app/Form/Form";
import { FormInsertEvent } from "app/app/Form/Form";

class EnhavoAdapter
{
    constructor()
    {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        $(document).on('formOpenAfter', function (event, element:HTMLElement) {
            DynamicForm.apply(element);
        });

        $(document).on('gridAddAfter', function (event, element:HTMLElement) {
            //DynamicForm.apply(element);
        });

        let listener = new FormListener();
        listener.onInsert(function(event: FormInsertEvent) {
            DynamicForm.apply(event.getElement());
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;