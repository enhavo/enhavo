import { DynamicForm, DynamicFormConfig } from 'app/DynamicForm';
import { FormListener } from "app/Form/Form";
import { FormInsertEvent } from "app/Form/Form";
import * as form from 'app/Form'
import { Media } from 'media/Media';

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

        let listener = new FormListener();
        listener.onRelease(function(event: FormInsertEvent) {
            DynamicForm.apply(event.getElement());
            form.initWysiwyg(event.getElement());
            form.initRadioAndCheckbox(event.getElement());
            form.initSelect(event.getElement());
            form.initDataPicker(event.getElement());
            form.initList(event.getElement());
            form.initAutoComplete(event.getElement());
            Media.apply(event.getElement());
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;