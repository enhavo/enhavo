import { Media } from 'media/Media';
import { enhavoAdapter as enhavoMediaAdapter } from 'media/Adapter/EnhavoAdapter';
import { Grid } from 'grid/Grid';
import * as form from 'app/Form'

class EnhavoAdapter
{
    constructor()
    {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        $(document).on('formOpenAfter', function (event, element:HTMLElement) {
            Grid.apply(element);
        });

        $(document).on('gridAddAfter', function (event, element:HTMLElement) {
            enhavoMediaAdapter.initMedia(element);
            form.initWysiwyg(element);
            form.initRadioAndCheckbox(element);
            form.initSelect(element);
            form.initDataPicker(element);
            form.initList(element);
        });
    }

    public initMedia(form:string|HTMLElement)
    {
        Media.apply(form);
    }
}

let adapter = new EnhavoAdapter();
export default adapter;