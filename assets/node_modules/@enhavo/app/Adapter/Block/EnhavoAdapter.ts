import { ListBlock } from '@enhavo/app-assets/Block/List';

class EnhavoAdapter
{
    constructor()
    {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        let lists : ListBlock[] = [];

        $('body').on('initBlock', function(event, data) {
            if(data.type == 'list') {
                lists.push(new ListBlock(data.block));
            }
        });

        $(document).on('formSaveAfter', function () {
            for(let list of lists) {
                list.load();
            }
            //admin.closeLoadingOverlay();
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;