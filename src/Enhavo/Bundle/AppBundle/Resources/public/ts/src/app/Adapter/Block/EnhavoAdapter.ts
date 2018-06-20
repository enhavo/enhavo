import { ListBlock } from 'app/app/Block/List';

class EnhavoAdapter
{
    constructor()
    {
        EnhavoAdapter.initFormListener();
    }

    private static initFormListener(): void
    {
        $('body').on('initBlock', function(event, data) {
            if(data.type == 'list') {
                new ListBlock(data.block);
            }
        });
    }
}

let adapter = new EnhavoAdapter();
export default adapter;