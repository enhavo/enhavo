import {Controller, Application} from "@hotwired/stimulus"
import {Product} from "@enhavo/shop/model/Product";
import {ProductManager} from "@enhavo/shop/manager/ProductManager";
import {ContainerInterface} from "@enhavo/dependency-injection/container/ContainerInterface";
import {CartManager} from "@enhavo/shop/manager/CartManager";
import {ProductVariant} from "@enhavo/shop/model/ProductVariant";

declare module '@hotwired/stimulus' {
    export class Application extends Application {
        container: ContainerInterface;
    }
    export class Controller extends Controller {
        application: Application
    }
}

export default class extends Controller
{
    productManager: ProductManager
    cartManager: CartManager

    protected productValue: Product;
    protected productVariantValue: ProductVariant;

    static values = {
        product: Object,
        productVariant: Object,
    }

    async initialize()
    {
        this.productManager = await this.application.container.get('@enhavo/shop/manager/ProductManager');
        this.cartManager = await this.application.container.get('@enhavo/shop/manager/CartManager');
    }

    public addToCart()
    {
        this.cartManager.addToCart(this.productVariantValue)
    }
}
