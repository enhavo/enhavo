export default interface LoaderInterface
{
    insert(element: HTMLElement): void;

    release(element: HTMLElement): void;

    drop(element: HTMLElement): void;

    move(element: HTMLElement): void;

    remove(element: HTMLElement): void;
}