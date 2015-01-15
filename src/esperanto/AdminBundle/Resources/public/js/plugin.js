(function ( $ ) {
  /**
   * ajaxForm
   *
   * option have this keys
   * options = {
   *   onlyOneItem : boolean
   *   after : function() {}
   * };
   *
   * @param options
   * @returns {$.fn}
   */
  $.fn.mediaUploadForm = function(options) {
    if(!options) {
      options = {};
    }

    var element = this;

    var sortMedia = function()
    {
      $(element).find(".imgList").sortable({
        items:"li.imgContainer",
        helper:"clone",
        update:function(e,ui) {
          var ids = [];

          $(this).find("li[fileid]").each(function() {
            ids.push($(this).attr("fileid"));
          });
          var data = {fileIds:ids};
          $.post("/ajax/media/sortMedia",data,function(response) {

          });
        }
      });
    };

    var handleDeleteImage = function()
    {
      console.log('test');
      $(element).find(".imgdelete").off("click").on("click", function() {
        console.log('test');
        var root = $(this).parents("li");
        var fileId = root.attr("fileid");
        var data = {fileId: fileId};
        $.post("/ajax/media/delete",data,function(response) {
          if(response.error) {
            overlayMessage(response.msg);
          } else {
            if($(".tinymceContent[id]").length) {
              $(".tinymceContent[id]").each(function() {
                var html = $(this).html();
                var regex = new RegExp('<img src="\/?media\/get\/'+fileId+'" (alt="[^"]*" )?\/>');
                html = html.replace(regex,"");
                $(this).html(html);
              });
            }

            root.slideUp(350,function() {
              $(this).remove();
            });
          }
        });
      });
    };

    $(this).submit(function(event) {
      var iFrame = $("#uploadIframe");
      var self = $(this);
      iFrame.off("load.xq");
      iFrame.on("load.xq",function() {
        var response = $(this).contents().find('body').text();
        response = $.parseJSON(response);
        if(response.error) {
          overlayMessage(response.msg);
        } else {
          if(options.onlyOneItem && self.find(".imgList").children().size() > 0) {
            self.find(".imgdelete").trigger("click.xq");
          }

          var imgItem = $("<li class=\"imgContainer\" fileid=\""+response.data.fileId+"\"><div class=\"imgdelete\"><i class=\"icon-remove icon-2x\"></i></div><img src=\"/media/thumb/"+response.data.fileId+"\" largesrc=\"/media/get/"+response.data.fileId+"\" /></li>");
          self.find(".imgList").append(imgItem);
          handleDeleteImage();
          sortMedia();

          if(options.after) {
            options.after();
          }
        }
      });
    });

    handleDeleteImage();
    sortMedia();
  };

  $.fn.overlayClose = function() {
    $(this).click(function() {
      admin.overlayClose();
    });
  };

  $.fn.parentRecursive = function(selector) {
    var parentRecursive = function(element) {
      if(element.is(selector)) {
        return element;
      }
      if(element.parent().get(0) == document) {
        return null;
      }
      return parentRecursive(element.parent());
    };
    return parentRecursive(this);
  };
}( jQuery ));