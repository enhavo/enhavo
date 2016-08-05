function Grid() {

  this.init = function() {
    this.setTitleHeightOfThreePictureItem();
  };

  this.setTitleHeightOfThreePictureItem = function () {
    var maxHeight = -1;

    var imageTitles = $('[data-image-title]');

    imageTitles.each(function () {
      maxHeight = maxHeight > $(this).height() ? maxHeight : $(this).height();
    });

    imageTitles.each(function () {
      $(this).height(maxHeight);
    });
  };

  this.init();
}

$(function(){
  new Grid();
});

