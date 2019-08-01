import * as $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class Theme implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(() => {
            this.initNavigation(element);
            this.showDiffBillAddressForm(element);
        })
    }

    private initNavigation(element: HTMLElement)
    {
        $(element).find('[data-show-menu]').on('click', function() {
            $('[data-menu-items]').toggle();
            $(this).toggleClass('active');
        });
    }

    private showDiffBillAddressForm(element: HTMLElement)
    {

        $(element).find('[data-billaddress-checkbox]').on('change', function() {
            console.log('zes');
            $(this).parent().parent().find('fieldset').fadeToggle();
        });
    }

}