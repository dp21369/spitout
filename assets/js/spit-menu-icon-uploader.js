jQuery(document).ready(function ($) {
  $("#upload_image_button").click(function (e) {
    e.preventDefault();
    var imageUploader = wp.media({
      title: "Upload Image",
      multiple: false,
    });

    imageUploader.on("select", function () {
      var attachment = imageUploader.state().get("selection").first().toJSON();
      $("#desktop_logo_field").val(attachment.url);
      $("#image_preview").attr("src", attachment.url);
    });

    imageUploader.open();
  });

  /* mobile logo */

  $("#mobile_upload_image_button").click(function (e) {
    e.preventDefault();
    var imageUploader = wp.media({
      title: "Upload Image",
      multiple: false,
    });

    imageUploader.on("select", function () {
      var attachment = imageUploader.state().get("selection").first().toJSON();
      $("#mobile_logo_field").val(attachment.url);
      $("#mobile_image_preview").attr("src", attachment.url);
    });

    imageUploader.open();
  });
  /*   footer logo  */

  $("#footer_upload_image_button").click(function (e) {
    e.preventDefault();
    var imageUploader = wp.media({
      title: "Upload Image",
      multiple: false,
    });

    imageUploader.on("select", function () {
      var attachment = imageUploader.state().get("selection").first().toJSON();
      $("#footer_logo_field").val(attachment.url);
      $("#footer_image_preview").attr("src", attachment.url);
    });

    imageUploader.open();
  });
});
