import { DynamicForm, DynamicFormConfig } from 'app/app/DynamicForm';

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
            DynamicForm.apply(element);
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;