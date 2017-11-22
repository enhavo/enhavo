import { Media } from 'media/Media';
import {Grid, GridConfig} from 'grid/Grid';
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
            let config = new GridConfig();
            config.scope = $(element).offsetParent().get(0);
            Grid.apply(element, );
            Media.apply(element);
        });

        $(document).on('gridAddAfter', function (event, element:HTMLElement) {
            Media.apply(element);
            form.initWysiwyg(element);
            form.initRadioAndCheckbox(element);
            form.initSelect(element);
            form.initDataPicker(element);
            form.initList(element);
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;