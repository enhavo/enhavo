import InitializerInterface from "@enhavo/app/InitializerInterface";
import $ from "jquery";
import "slick-carousel";

export default class GalleryBlock implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        if($('[data-hero-slider]').length > 0) {
            $(element).find('[data-hero-slider]').slick({
                nextArrow: '<div class="arrow next"></div>',
                prevArrow: '<div class="arrow prev"></div>',
                fade: true,
                dots:true
            });
        }
    }
}