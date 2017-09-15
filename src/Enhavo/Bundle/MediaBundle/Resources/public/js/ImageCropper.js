define(['jquery', 'app/Router', 'app/Admin', 'cropper'], function($, router, admin, cropper) {

  return function(uploadForm) {
    var self = this;
    this.imageShowRoute = $(uploadForm).find('[data-image-show-route]').data('image-show-route');
    this.callbackDone = null;
    this.callbackCanceled = null;
    this.$cropperCanvas = $(uploadForm).find('[data-image-crop-canvas]');
    this.mimeType = null;

    this.startImageCrop = function (selected, callbackDone, callbackCanceled) {
      if (typeof selected != 'undefined' && selected != null) {
        self.callbackDone = callbackDone;
        self.callbackCanceled = callbackCanceled;
        var fileId = selected.data('id');
        self.mimeType = selected.find('[data-mime-type]').data('mime-type');

        var preventCachingNumber = Math.random();
        var url = router.generate(self.imageShowRoute, {id: fileId, v: preventCachingNumber});
        console.log(url);
        self.$cropperCanvas.attr('src', url);

        $(uploadForm).find('[data-image-crop-canvas-wrapper]').addClass('loading');
        $(uploadForm).find('[data-image-crop-overlay]').show();
        self.$cropperCanvas.cropper();
      } else {
        console.log('Image cropper error: No target image selected');
        callbackCanceled();
      }
    };

    this.$cropperCanvas.on('built.cropper', function () {
      // Finished loading
      $(uploadForm).find('[data-image-crop-canvas-wrapper]').removeClass('loading');
    });

    this.cleanup = function () {
      $(uploadForm).find('[data-image-crop-overlay]').hide();
      self.$cropperCanvas.cropper('destroy');
      self.callbackDone = null;
      self.callbackCanceled = null;
    };

    this.executeCrop = function () {
      var callback = self.callbackDone;
      var result = self.$cropperCanvas.cropper('getCroppedCanvas').toDataURL('image/png');  // Use png to allow transparent background
      self.cleanup();
      admin.closeLoadingOverlay();
      if (callback != null) {
        callback(result);
      }
    };

    $(uploadForm).find('[data-image-cropper-action]').click(function (event) {
      event.stopPropagation();
      event.preventDefault();

      switch ($(this).data("image-cropper-action")) {
        case "move-mode":
          $(this).addClass('selected');
          $(uploadForm).find('[data-image-cropper-action="cropframe-mode"]').removeClass('selected');
          self.$cropperCanvas.cropper('setDragMode', 'move');
          break;
        case "cropframe-mode":
          $(this).addClass('selected');
          $(uploadForm).find('[data-image-cropper-action="move-mode"]').removeClass('selected');
          self.$cropperCanvas.cropper('setDragMode', 'crop');
          break;
        case "zoom-in":
          self.$cropperCanvas.cropper('zoom', '0.1');
          break;
        case "zoom-out":
          self.$cropperCanvas.cropper('zoom', '-0.1');
          break;
        case "reset":
          self.$cropperCanvas.cropper('reset');
          break;
      }
    });

    $(uploadForm).find('[data-image-crop-submit]').click(function (event) {
      event.stopPropagation();
      event.preventDefault();

      admin.openLoadingOverlay();

      window.setTimeout(self.executeCrop, 500);
    });

    $(uploadForm).find('[data-image-crop-cancel]').click(function (event) {
      event.stopPropagation();
      event.preventDefault();

      var callback = self.callbackCanceled;

      self.cleanup();
      if (callback != null) {
        callback();
      }
    });

    $(uploadForm).find('[data-image-crop-overlay]').click(function (event) {
      event.stopPropagation();
    });
  };
});
