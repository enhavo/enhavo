import * as $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class Theme implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(() => {
            this.initNavigation(element);
        })
    }

    private initNavigation(element: HTMLElement)
    {
        $(element).find('[data-show-menu]').on('click', function() {
            $('[data-menu-items]').toggle();
            $(this).toggleClass('active');
        });
    }
}