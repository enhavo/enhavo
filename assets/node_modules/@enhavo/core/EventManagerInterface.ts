export interface EventManagerInterface {
    dispatchEvent(name: string, event: Event): void;
    on(name: string, handle: (event: Event) => void);
}