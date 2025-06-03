export default {};

export function onPreviewRefresh(callback: () => void)
{
    document.addEventListener('preview-refresh', () => {
        callback();
    })
}
