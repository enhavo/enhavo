export interface Viewport {
    width: number;
    height: number;
}

export interface OverlayOptions {

}

export interface MessageType {
    Info: string;
    Error: string;
    Success: string;
}

export interface Admin {
    viewport(): Viewport;
    initOverlay: void;
    initEscButton: void;
    overlay(html: string, options: OverlayOptions): void;
    overlayClose(): void;
    ajaxOverlay(url:string, options:OverlayOptions): void
    iframeOverlay(form:string, url:string, options:OverlayOptions): void;
    iframeClose(form:string, originalAction:string): void;
    overlayMessage(content:string, type:MessageType): void;
    initActions(): void;
    initTabs(): void;
    initAfterSaveHandler(): void;
    initBlocks(): void;
    initNavigation(): void;
    initUserMenu(): void;
    initDescriptionTextPosition(): void;
    openLoadingOverlay(): void;
    closeLoadingOverlay(): void;
    confirm(message:string, callback:() => void): void;
    alert(message:string): void;
}