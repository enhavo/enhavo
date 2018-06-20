import * as $ from "jquery";
import * as router from 'app/Router'

export class ListBlock
{
    private $element: JQuery;

    private $container: JQuery;

    private route: string;

    constructor(element: HTMLElement) 
    {
        this.$element = $(element);
        this.route = this.$element.data('block-list-route');
        this.$container = this.$element.find('[data-list]');
        this.load();
    }

    public load()
    {
        this.$element.addClass('loading');
        this.getList(null);
    }

    private getList(parent: number)
    {
        let self = this;
        let url = router.generate(this.route, {
            parent: parent
        });

        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                self.$element.removeClass('loading');
                self.addToList(data);
            }
        });
    }

    private addToList(html: string)
    {
        this.$container.html(html);
    }
}