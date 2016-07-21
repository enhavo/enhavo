function Grid() {

  this.init = function() {
    this.setTitleHeightOfThreePictureItem();
  }

  this.setTitleHeightOfThreePictureItem = function () {
    var maxHeight = -1;

    $('[data-image-title]').each(function () {
      maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
    });

    $('[data-image-title]').each(function () {
      $(this).height(maxHeight);
    });
  }

  this.init();
}

$(function(){
  Grid();
});

