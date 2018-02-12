import * as $ from 'jquery'

export class Menu
{
    private $element: JQuery;

    private list: List[] = [];

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.initList();
    }

    public initList()
    {
        let self = this;
        this.$element.find('[data-menu-list]').each(function(index, element:HTMLElement) {
            self.list.push(new List(element));
        });
    }

    public static init()
    {
        let element = $('[data-menu]').get(0);
        let menu = new Menu(element);
    }
}

export class List
{
    private $element: JQuery;
    
    private openFlag: boolean;

    constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.openFlag = this.$element.hasClass('open');
        this.init();
    }

    private init()
    {
        let self = this;
        this.$element.find('[data-menu-list-button]').click(function() {
            if(self.isOpen()) {
                self.close();
            } else {
                self.open();
            }
        });
    }

    public isOpen() : boolean
    {
        return this.openFlag;
    }

    public open() : void
    {
        this.openFlag = true;
        this.$element.addClass('open');
        this.$element.find('[data-menu-list-container]').show();
    }

    public close() : void
    {
        this.openFlag = false;
        this.$element.removeClass('open');
        this.$element.find('[data-menu-list-container]').hide();
    }
}

Menu.init();