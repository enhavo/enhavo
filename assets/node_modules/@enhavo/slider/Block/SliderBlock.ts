import InitializerInterface from "@enhavo/app/InitializerInterface";
import * as $ from "jquery";

export default class GalleryBlock implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(element).find('[data-hero-slider]').slick({
            nextArrow: '<div class="arrow next"></div>',
            prevArrow: '<div class="arrow prev"></div>',
            fade: true,
            dots:true
        });
    }
}