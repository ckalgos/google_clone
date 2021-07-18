$(document).ready(function () {
  var grid = $(".images-results").imagesLoaded(function () {
    $(grid).masonry({
      // options
      itemSelector: ".image-result-container",
      columnWidth: 300,
    });
  });
  $("[data-fancybox]").fancybox({
    caption: function (self, image) {
      var caption = $(this).data("caption") || "View Image";
      caption = "<a href=" + image.src + " target='_blank'>" + caption + "</a>";
      return caption;
    },
    afterShow: function (self, image) {
      var id = $(image.opts.$orig[0]).data("id");

      $.post("updateVisitedCount.php", {
        id: id,
        type: "images",
      }).done(function () {});
    },
  });

  $(".title-url").on("click", function () {
    var id = $(this).data("id");
    var url = $(this).attr("href");

    $.post("updateVisitedCount.php", {
      id: id,
      type: "sites",
    }).done(function () {
      window.location.href = url;
    });

    return false;
  });
});
