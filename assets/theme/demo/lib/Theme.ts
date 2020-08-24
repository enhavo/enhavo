import * as $ from "jquery";
import InitializerInterface from "@enhavo/app/InitializerInterface";

export default class Theme implements InitializerInterface
{
    public init(element: HTMLElement)
    {
        $(() => {
            this.initNavigation(element);
            this.showDiffBillAddressForm(element);
            this.handleLoadingCursor(element);
            this.toggleSidebar(element);
            this.inspectShopImage(element);
            this.handleShopGallery(element);
        })
    }

    private handleShopGallery (element: HTMLElement)
    {
        $(element).find('[data-image]').on('click', function() {
            let imgSrc = $(this).attr('src');
            let fullImgSrc = $(this).data('full-image');

            $(this).parent().parent().parent('[data-shop-gallery]').find('[data-image-zoom]').attr('src', imgSrc)
            $(this).parent().parent().parent('[data-shop-gallery]').find('[data-image-zoom]').attr('data-image-zoom', fullImgSrc)
        });
    };

    private inspectShopImage (element: HTMLElement)
    {
        $(element).find('[data-image-zoom]').on('click', function() {
            let imgSrc = $(this).attr('data-image-zoom');
            $('[data-product-zoom]').fadeIn();
            $('[data-product-zoom]').find('[data-image-zoomed]').attr('src', imgSrc);
        });


        $(element).find('[data-product-zoom]').on('mousemove', function(event) {

            let windowWidth = window.innerWidth;
            let windowHeight = window.innerHeight;

            let imgWidth = $(this).find('[data-image-zoomed]').innerWidth();
            let imgHeight = $(this).find('[data-image-zoomed]').innerHeight();

            let cursorX = event.pageX / windowWidth;
            let cursorY = event.pageY / windowHeight;

            let imageWindowDiffWidth = (imgWidth - windowWidth)
            let imageWindowDiffHeight = (imgHeight - windowHeight)

            let ImgPositionX = cursorX * imageWindowDiffWidth;
            let ImgPositionY = cursorY * imageWindowDiffHeight;

            // bild, das höher und breiter als das Fenster ist
            if(imgHeight > windowHeight && imgWidth > windowWidth) {
                $(this).find('[data-image-zoomed]').css({'left':-ImgPositionX, 'top': -ImgPositionY, 'transform':'none'});
            };
            // bild, das nicht höher als das Fenster ist, aber breiter
            if(imgHeight < windowHeight && imgWidth > windowWidth) {
                $(this).find('[data-image-zoomed]').css({'left':-ImgPositionX, 'top':'50%', 'transform':'translateY(-50%)'});
            };
            // bild, das nicht breiter als das Fenster ist, aber höher
            if(imgHeight > windowHeight && imgWidth < windowWidth) {
                $(this).find('[data-image-zoomed]').css({'left':'50%', 'transform':'translateX(-50%)', 'top': -ImgPositionY});
            };
        });

        $(element).find('[data-product-zoom]').on('click', function() {
            $(this).fadeOut();
        });
    };

    private initNavigation(element: HTMLElement)
    {
        $(element).find('[data-show-menu]').on('click', function() {
            $('[data-menu-items]').toggle();
            $(this).toggleClass('active');
        });
    };

    private toggleSidebar(element: HTMLElement)
    {
        let showCart = false;
        let sidebar = $('[data-cart-sidebar]');
        let sidebarContent = $('[data-cart-sidebar] *');
        let body = $('body');

        $(element).find('[data-show-cart]').on('click', function(e) {
            e.preventDefault();
            if(showCart == false) {
                body.addClass('sidebar-visible');
                setTimeout(function() {
                    showCart = true;
                },50);
            }
        });
        $(element).on('click', function(e) {
            if(showCart == true) {
                let target = $(e.target);
                if(!target.is(sidebar) && !target.is(sidebarContent)) {
                    body.removeClass('sidebar-visible');
                    showCart = false;
                }
            }
        });
    };

    private showDiffBillAddressForm(element: HTMLElement)
    {

        $(element).find('[data-billaddress-checkbox]').on('change', function() {
            $(this).parent().parent().find('fieldset').fadeToggle();
        });
    }

    private handleLoadingCursor(element: HTMLElement)
    {
        $('[data-loading-screen]').on('mouseover', function() {
            $(this).find('[data-loading-spinner]').fadeIn();
            document.onmousemove = handleMouseMove;
            function handleMouseMove(event: any) {

                let eventDoc, doc, body;
                event = event || window.event;

                if (event.pageX == null && event.clientX != null) {
                    eventDoc = (event.target && event.target.ownerDocument) || document;
                    doc = eventDoc.documentElement;
                    body = eventDoc.body;

                    event.pageX = event.clientX +
                        (doc && doc.scrollLeft || body && body.scrollLeft || 0) -
                        (doc && doc.clientLeft || body && body.clientLeft || 0);
                    event.pageY = event.clientY +
                        (doc && doc.scrollTop  || body && body.scrollTop  || 0) -
                        (doc && doc.clientTop  || body && body.clientTop  || 0 );
                }

                let element = <HTMLElement>document.getElementsByClassName('loading')[0];

                element.style.top=event.pageY+'px';
                element.style.left=event.pageX+'px';
            }
        });
        $('[data-loading-screen]').on('mouseleave', function() {
            $('[data-loading-spinner]').fadeOut();
        });
    };


}