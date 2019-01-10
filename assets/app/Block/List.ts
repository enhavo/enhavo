import * as $ from "jquery";
import * as router from 'app/Router'
import * as admin from 'app/Admin'

export class ListBlock
{
    private $element: JQuery;

    private $container: JQuery;

    private route: string;

    private updateRoute: string;

    private updateRouteParameters: RouteParameters;

    private items: ListItem[] = [];

    constructor(element: HTMLElement) 
    {
        this.$element = $(element);
        this.route = this.$element.data('block-list-route');
        this.updateRoute = this.$element.data('block-update-route');
        this.updateRouteParameters = this.$element.data('block-update-route-parameters');
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
        this.items = [];
        let self = this;

        this.$container.html(html);
        this.$container.find('[data-list-item]').each(function(index, element:HTMLElement) {
            self.items.push(new ListItem(element, self.updateRoute, self.updateRouteParameters));
        });
    }
}

class ListItem
{
    private $element: JQuery;

    private $children: JQuery;

    private route: string;

    private routeParameters: RouteParameters;

    private id: number;

    constructor(element: HTMLElement, route: string, routeParameters: RouteParameters)
    {
        this.$element = $(element);
        this.$children = this.$element.find('[data-list-children]');
        this.id = this.$element.children('[data-item]').data('item');
        this.route = route;
        this.routeParameters = routeParameters ? routeParameters: new RouteParameters;
        this.routeParameters.id = this.id;

        this.initListener();
    }

    private initListener()
    {
        let self = this;

        this.$element.children('[data-item]').click(function(event) {
            let $target = $(event.target);

            // check if link
            let isLink = false;
            $target.parentsUntil('[data-item-id]').each(function() {
                if($(this).is('a')) {
                    isLink = true;
                }
            });
            if($target.is('a')) {
                isLink = true;
            }
            if(isLink) {
                return true;
            }

            self.open();
        });

        this.$element.children('[data-item]').find('[data-collapse] a').click(function(event) {
            event.preventDefault();
            self.collapse();
        });

        this.$element.children('[data-item]').find('[data-expand] a').click(function(event) {
            event.preventDefault();
            self.expand();
        });
    }

    private open()
    {
        if(this.route) {
            let url = router.generate(this.route, this.routeParameters);
            admin.ajaxOverlay(url, {});
        }
    }

    public collapse()
    {
        this.$element.removeClass('expand');
    }

    public expand()
    {
        this.$element.addClass('expand');
    }
}

class RouteParameters {
    id: number;
}

