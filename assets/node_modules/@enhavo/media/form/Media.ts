import * as $ from 'jquery'
import axios from "axios";
import * as async from "async";

export class Media
{
    protected $element: JQuery;
    protected config: any;

    public constructor(element: HTMLElement)
    {
        this.$element = $(element);
        this.config = this.$element.data('media');

        this.initHighlight();
        this.initDropzone();
        this.initUploadButton();
        this.initRemoveButton();
    }

    private initUploadButton()
    {
        this.$element.find('[data-media-upload]').on('click', (event) => {
            event.preventDefault();
            this.$element.find('[data-media-input]').trigger('click');
        });

        this.$element.find('[data-media-input]').on('change', (event) => {
            event.preventDefault();

            let $formElement = $(document.createElement("form"));
            let $input = this.$element.find('[data-media-input]');

            if ($input.length) {
                $formElement.append($input.get(0));
            }

            $formElement.on('submit', (event) => {
                event.preventDefault();
                this.upload(null, <HTMLFormElement>$formElement.get(0)).then(() => {
                    this.$element.append($input.get(0));
                    $formElement.remove();
                });
            });

            this.$element.append($formElement.get(0));

            $formElement.submit();
        });
    }

    private initRemoveButton()
    {
        this.$element.find('[data-media-item]').each(function() {
            let $item = $(this)
            $item.find('[data-media-remove]').on('click', (event) => {
                event.preventDefault();
                $item.remove();
            });
        });
    }

    private initHighlight()
    {
        $(window).on('dragenter dragover', () => {
            this.$element.addClass('highlight');
        });

        $(window).on('dragleave', () => {
            this.$element.removeClass('highlight');
        });
    }

    private initDropzone()
    {
        this.$element.on('dragover', (event) => {
            event.preventDefault();
            this.$element.addClass('drop-over');
        }).on('dragleave', (event) => {
            event.preventDefault();
            this.$element.removeClass('drop-over');
        }).on('drop dragdrop', (event) => {
            event.preventDefault();
            this.$element.removeClass('drop-over');
            this.$element.removeClass('highlight');
        }).on('drop', (event) => {
            event.preventDefault();
            let files = event.originalEvent.dataTransfer.files;
            this.$element.addClass('loading');
            this.upload(files).then(() => {
                this.$element.removeClass('loading');
            }).catch(() => {
                this.$element.removeClass('loading');
            });
        });
    }

    private upload(files: any, formElement: HTMLFormElement = null)
    {
        return new Promise((resolve, reject) => {
            let callbacks: ((callback) => void)[] = [];

            if (files) {
                if (!this.config.multiple) {
                    files = [files[0]];
                }

                for (let file of files) {
                    let data = new FormData();
                    data.append('files', file);
                    callbacks.push(this.createCallback(data));
                }

                async.parallel(callbacks, (err) => {
                    if (err) {
                        reject(err);
                        return;
                    }
                    resolve();
                });
            } else {
                let data = new FormData(formElement);
                this.createCallback(data)((data, err) => {
                    if (err) {
                        reject(err);
                        return;
                    }
                    resolve();
                });
            }
        });
    }

    private createCallback(data: any): (callback: any) => void[]
    {
        let url = '/file/add';

        return (callback) => {
            axios.post(url, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then((response) => {
                for (let file of response.data) {
                    this.addFile(file);
                }
                callback();
            })
            .catch((error) => {
                console.error(error)
                callback(null, error)
            });
        }
    }

    protected addFile(file: any)
    {
        let $list = this.$element.find('[data-media-list]');
        
        if (!this.config.multiple) {
            $list.html('');
        }

        let prototype = $list.data('prototype');
        let index = $list.find('[data-media-item]').length;

        prototype = prototype.trim();
        prototype = prototype.replaceAll('__index__', index);
        prototype = prototype.replaceAll('__id__', file.id);
        prototype = prototype.replaceAll('__order__', '');
        prototype = prototype.replaceAll('__filename__', file.filename);

        let $element = $($.parseHTML(prototype));
        $element.find('[data-media-remove]').on('click', () => {
            $element.remove();
        })
        $list.append($element.get(0));
    }
}
