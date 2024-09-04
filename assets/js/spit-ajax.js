jQuery(document).ready(function () {
  if (jQuery("#profile-cover-uploader").length > 0) {
    jQuery("#profile-cover-uploader").on("click", function (event) {
      document.getElementById("so-cover-img").click();
    });
  }

  if (jQuery("#profile-picture-uploader").length > 0) {
    jQuery("#profile-picture-uploader").on("click", function (event) {
      document.getElementById("so-profile-img").click();
    });
  }

  if (jQuery("#so_create_post_icon").length > 0) {
    jQuery("#so_create_post_icon").on("click", function (event) {
      document.getElementById("so_create_post_media").click();
    });
  }

  //===============CROPPER STARTS==========

  jQuery("#btnCrop, #btnRestore, .update-profile-btn").hide();
  jQuery("#btnBannerCrop,#btnBannerRestore,.update-banner-btn").hide();

  function dataURLtoBlob(dataurl) {
    var arr = dataurl.split(","),
      mime = arr[0].match(/:(.*?);/)[1],
      bstr = atob(arr[1]),
      n = bstr.length,
      u8arr = new Uint8Array(n);
    while (n--) {
      u8arr[n] = bstr.charCodeAt(n);
    }
    return new Blob([u8arr], { type: mime });
  }

  // if (jQuery("#cropper_canvas").length != 0) {
  //   var canvas = jQuery("#cropper_canvas");
  //   var context = canvas.get(0).getContext("2d");
  // }

  jQuery("#so-profile-img").on("change", function () {
    jQuery("#btnCrop, #btnRestore, .update-profile-btn").show();

    var canvas = jQuery("#cropper_canvas");
    var context = canvas.get(0).getContext("2d");

    canvas.show();

    if (this.files && this.files[0]) {
      if (this.files[0].type.match(/^image\//)) {
        let reader = new FileReader();
        reader.onload = function (evt) {
          let img = new Image();
          img.onload = function () {
            context.clearRect(
              0,
              0,
              context.canvas.width,
              context.canvas.height
            );
            context.canvas.height = img.height;
            context.canvas.width = img.width;
            context.drawImage(img, 0, 0);

            // Destroy the old cropper instance
            canvas.cropper("destroy");

            // Replace url
            canvas.attr("src", this.result);

            var cropper = canvas.cropper({
              aspectRatio: 1 / 1,
            });

            jQuery("#btnCrop").click(function () {
              // Get a string base 64 data url
              let croppedImageDataURL = canvas
                .cropper("getCroppedCanvas")
                .toDataURL("image/png");
              jQuery("#selected-profile-image-preview").attr(
                "src",
                URL.createObjectURL(dataURLtoBlob(croppedImageDataURL))
              );
            });
            jQuery("#btnRestore").click(function () {
              canvas.cropper("reset");
            });
          };
          img.src = evt.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      } else {
        alert("Invalid file type! Please select an image file.");
      }
    } else {
      alert("No file(s) selected.");
    }
  });

  /* When the user choose the profile picture it act as a preview */
  jQuery("#so-profile-img").on("change", function (event) {
    // readURL(this);

    const selectedImage = event.target.files[0];

    const selectedImagePreview = jQuery("#selected-profile-image-preview");

    if (selectedImage) {
      selectedImagePreview.attr("src", URL.createObjectURL(selectedImage));
    }
  });

  if (jQuery("#cropper_canvas_banner").length != 0) {
    var bannerCanvas = jQuery("#cropper_canvas_banner");
    var bannerContext = bannerCanvas.get(0).getContext("2d");
  }

  jQuery("#so-cover-img").on("change", function () {
    jQuery("#btnBannerCrop,#btnBannerRestore,.update-banner-btn").show();

    bannerCanvas.show();

    if (this.files && this.files[0]) {
      if (this.files[0].type.match(/^image\//)) {
        let reader = new FileReader();
        reader.onload = function (evt) {
          let img = new Image();
          img.onload = function () {
            bannerContext.clearRect(
              0,
              0,
              bannerContext.canvas.width,
              bannerContext.canvas.height
            );
            bannerContext.canvas.height = img.height;
            bannerContext.canvas.width = img.width;
            bannerContext.drawImage(img, 0, 0);

            // Destroy the old cropper instance
            bannerCanvas.cropper("destroy");

            // Replace url
            bannerCanvas.attr("src", this.result);

            var cropper = bannerCanvas.cropper({
              aspectRatio: 1 / 1,
            });

            jQuery("#btnBannerCrop").click(function () {
              // Get a string base 64 data url
              let croppedImageDataURL = bannerCanvas
                .cropper("getCroppedCanvas")
                .toDataURL("image/png");
              jQuery("#selected-cover-preview").attr(
                "src",
                URL.createObjectURL(dataURLtoBlob(croppedImageDataURL))
              );
            });
            jQuery("#btnBannerRestore").click(function () {
              bannerCanvas.cropper("reset");
            });
          };
          img.src = evt.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      } else {
        alert("Invalid file type! Please select an image file.");
      }
    } else {
      alert("No file(s) selected.");
    }
  });

  //===============CROPPER ENDS==========

  /* When the user choose the banner picture it act as a preview */
  jQuery("#so-cover-img").on("change", function (event) {
    const selectedImage = event.target.files[0];
    const selectedImagePreview = jQuery("#selected-cover-preview");

    if (selectedImage) {
      selectedImagePreview.attr("src", URL.createObjectURL(selectedImage));
    }
  });

  jQuery(document).on(
    "click",
    ".so-follow-seller, .so-following-seller",
    function () {
      var $element = jQuery(this);
      var authorId = $element.data("author-id");
      var followerCount = $element.data("followers-count");
      ajaxUrl = spit_ajax.ajax_url;

      jQuery(".so-feed-following-loader").show();
      $element.text("");

      var action = $element.hasClass("so-follow-seller")
        ? "follow_author"
        : "unfollow_author";
      var textBefore = $element.hasClass("so-follow-seller")
        ? "Follow"
        : "Unfollow";
      var textAfter = $element.hasClass("so-follow-seller")
        ? "Unfollow"
        : "Follow";
      var newClass = $element.hasClass("so-follow-seller")
        ? "so-following-seller"
        : "so-follow-seller so-notify";

      // Perform an AJAX request
      jQuery.ajax({
        url: ajaxUrl,
        type: "POST",
        data: {
          action: action,
          author_id: authorId,
        },
        success: function (response) {
          jQuery(".so-feed-following-loader").hide();
          $element.text(textAfter);
          $element
            .removeClass("so-follow-seller so-following-seller so-notify")
            .addClass(newClass);

          if (action === "follow_author") {
            jQuery(".so-user-followers-count").text(followerCount + 1);
          } else {
            jQuery(".so-user-followers-count").text(followerCount);
          }
        },
      });
    }
  );

  /* The code change the post-status of the post from published to draft */
  jQuery(document).on("click", "#hide-button", function () {
    var button = jQuery(this);
    jQuery(".spitout-post-status-draft").css("display", "none");
    var postId = jQuery(this).data("post-id");
    ajaxUrl = spit_ajax.ajax_url;
    jQuery.ajax({
      url: ajaxUrl,
      type: "POST",
      data: {
        action: "so_change_post_status",
        post_id: postId,
      },
      success: function (response) {
        // Parse the response
        var data = JSON.parse(response);

        if (data.status == "success") {
          // Update the button text based on the new post status
          var newText = data.new_status == "publish" ? "Hide" : "Unhide";
          jQuery("#hide-button h6").text(newText);

          if (data.new_status == "publish") {
            button.removeClass("post-status-hidden"); // remove the class
          } else {
            button.addClass("post-status-hidden"); // add the class
          }
        } else {
          // Handle error
          console.error("Error changing post status");
        }
      },
    });
  });

  /* The code snippet is handling the submission of a form with the id "soBannerImg" i.e. this updates cover or banner images from seller profile */
  jQuery("#soBannerImg").submit(function (e) {
    e.preventDefault();
    ajaxUrl = spit_ajax.ajax_url;

    var fd = new FormData();
    fetch(jQuery("#selected-cover-preview").attr("src"))
      .then((response) => response.blob())
      .then((blob) => {
        var file = new File([blob], "banner.png", { type: "image/png" });
        fd.append("file", file);

        fd.append("action", "so_feed_cover_img_update");

        jQuery.ajax({
          url: ajaxUrl,
          type: "POST",
          data: fd,
          contentType: false,
          processData: false,
          beforeSend: function () {
            // Change the button value to "Updating"
            jQuery(".update-image-btn").val("Updating");
            jQuery(".update-image-btn").css("pointer-events", "none");
          },
          success: function (response) {
            jQuery("#feed_banner_image").attr("src", response.data);
            jQuery("#editCoverImageModal").modal("hide");
          },
          complete: function () {
            // Change the button value back to "UPDATE"
            jQuery(".update-image-btn").val("UPDATE");
            jQuery(".update-image-btn").css("pointer-events", "auto");
          },
        });
      });
  });

  /* The code snippet is handling the submission of a form with the id "soProfileImage" i.e used to update profile image from seller profile */
  jQuery("#soProfileImage").submit(function (e) {
    e.preventDefault();
    ajaxUrl = spit_ajax.ajax_url;

    var fd = new FormData();
    fetch(jQuery("#selected-profile-image-preview").attr("src"))
      .then((response) => response.blob())
      .then((blob) => {
        var file = new File([blob], "profile.png", { type: "image/png" });
        fd.append("file", file);

        fd.append("action", "so_feed_profile_img_update");
        jQuery.ajax({
          url: ajaxUrl,
          type: "POST",
          data: fd,
          contentType: false,
          processData: false,
          beforeSend: function () {
            // Change the button value to "Updating"
            jQuery(".update-image-btn").val("Updating");
            jQuery(".update-image-btn").css("pointer-events", "none");
          },
          success: function (response) {
            jQuery("#feed_profile_picture").attr("src", response.data);
            jQuery("#editFrofileImageModal").modal("hide");
          },
          complete: function () {
            // Change the button value back to "UPDATE"
            jQuery(".update-image-btn").val("UPDATE");
            jQuery(".update-image-btn").css("pointer-events", "auto");
          },
        });
      });
  });

  /* The above code is a JavaScript code snippet that handles the submission of a form. create post feed */
  jQuery(document).on("click", "#so-feed-submit-form", function (e) {
    // jQuery("#so-feed-submit-form").on("click", function (e) {
    e.preventDefault();

    var fd = new FormData();
    filesArray.forEach((file, index) => {
      fd.append("file[]", file);
    });

    ajaxUrl = spit_ajax.ajax_url;
    // var postContent = jQuery("#post-content").val();
    var preFormatValue = document
      .getElementById("post-content")
      .value.split("\n");
    var postContent = preFormatValue.join("<br />");
    jQuery("#so-feed-loading-icon").show();
    // jQuery("#so-feed-submit-form").hide();
    jQuery("#so-feed-submit-form").prop("disabled", true);

    var sender = jQuery(".so-create-posts").data("sender");
    var receiver = jQuery(".so-create-posts").data("receiver");
    var notificationType = jQuery(".so-create-posts").data("notify");

    setAjaxInProgress(true);

    var fd = new FormData();
    // multiple files start
    var files = jQuery("#so_create_new_post").find('input[type="file"]')[0]
      .files;

    var imageCount = 0;
    var videoCount = 0;

    for (var i = 0; i < files.length; i++) {
      if (files[i].type.match("image.*")) {
        imageCount++;
      } else if (files[i].type.match("video.*")) {
        videoCount++;
      }
    }

    if (imageCount > 10) {
      jQuery(".so-fallback-create-post-message").html(
        "<div class='so-create-feed-maximum-img'>You can only upload a maximum of 10 images</div>"
      );
      return false;
    } else if (videoCount > 1) {
      jQuery(".so-fallback-create-post-message").html(
        "<div class='so-create-feed-maximum-img'>You can only upload a maximum of 1 video</div>"
      );
      return false;
    }

    for (var i = 0; i < files.length; i++) {
      fd.append("file[]", files[i]);
    }
    // multiple files end
    fd.append("action", "so_create_new_posts");
    fd.append("postContent", postContent);

    var xhr = new XMLHttpRequest();

    xhr.upload.addEventListener("progress", function (event) {
      if (event.lengthComputable) {
        var percentage = (event.loaded / event.total) * 100;
        jQuery(".spitout-progress-bar-wrapper").show(); // Show the progress bar container
        jQuery(".spitout-progress-bar").css(
          "width",
          percentage.toFixed(2) + "%"
        );
      }
    });

    xhr.open("POST", ajaxUrl, true);
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        setAjaxInProgress(false);
        // Parse the response JSON to get the post ID
        var response = JSON.parse(xhr.responseText);

        jQuery("#spitout-feed-type").after(response.data.html);
        jQuery("#post-content").val(""); // Clear the text area
        jQuery("#so_create_post_media").val(""); // Clear the file input
        jQuery(".spitout-feed-img-prev").attr("src", ""); // Reset image preview
        jQuery(".image-preview-container").hide(); // Hide the image preview container

        jQuery(".spitout-feed-post-message").text("");
        jQuery(".spitout-percentage").text("");
        jQuery(".spitout-progress-bar-wrapper").hide();
        jQuery("#so-feed-submit-form").prop("disabled", false);
        jQuery.ajax({
          url: spit_ajax.ajax_url,
          type: "POST",
          data: {
            action: "so_notification",
            so_sender: sender,
            so_receiver: receiver,
            so_notification_type: notificationType,
            postID: response.data.post_id, // Use the post ID from the response
          },
          success: function (response) {
            // console.log(response);
          },
        });
      }
    };

    xhr.send(fd);
  });

  // /**
  //  * Event handler for file input changes
  //  * Preview selected images and show clear icons to remove images
  //  */
  // var filesArray = []; // Array to store selected files
  // jQuery("#so_create_post_media").on("change", function (e) {
  //   var files = e.target.files; // Get selected files
  //   var imagePreviewContainer = jQuery(".image-preview-container"); // Get image preview container

  //   imagePreviewContainer.empty(); // Clear previous image previews
  //   filesArray = Array.from(e.target.files); // Store selected files in filesArray

  //   // Iterate through each selected file
  //   for (var i = 0; i < files.length; i++) {
  //     var file = files[i]; // Current file being processed
  //     if (file) {
  //       var reader = new FileReader(); // FileReader to read file contents
  //       reader.onload = (function (file) {
  //         return function (e) {
  //           // Create image element for selected file
  //           var img = document.createElement("img");
  //           img.setAttribute("class", "spitout-feed-img-prev");
  //           img.setAttribute("src", e.target.result);

  //           // Create clear icon element to remove image
  //           var clearIcon = document.createElement("span");
  //           clearIcon.setAttribute("class", "clear-image-icon");
  //           clearIcon.innerHTML = '<i class="bi bi-x-circle"></i>';

  //           // Add click event to clear icon to remove image and icon
  //           clearIcon.addEventListener("click", function () {
  //             jQuery(img).remove(); // Remove image
  //             jQuery(clearIcon).remove(); // Remove clear icon
  //             filesArray = filesArray.filter((f) => f !== file); // Remove file from filesArray

  //             // Check if there are no images, then hide the container
  //             if (imagePreviewContainer.find("img").length === 0) {
  //               imagePreviewContainer.css("display", "none");
  //             }
  //           });

  //           // Create container for image and clear icon
  //           var imgContainer = document.createElement("div");
  //           imgContainer.setAttribute("class", "so-clear-image-icon-wrapper");
  //           imgContainer.appendChild(img);
  //           imgContainer.appendChild(clearIcon);

  //           // Append image container to image preview container
  //           imagePreviewContainer.append(imgContainer);
  //         };
  //       })(file);
  //       reader.readAsDataURL(file); // Read file as data URL
  //     }
  //   }

  //   // Show the image preview container if there are images
  //   if (files.length > 0) {
  //     imagePreviewContainer.css("display", "flex");
  //   } else {
  //     // Hide the image preview container if there are no images
  //     imagePreviewContainer.css("display", "none");
  //   }
  // });
  /**
   * Event handler for file input changes
   * Preview selected images and videos, and show clear icons to remove files
   */
  var filesArray = [];

  jQuery("#so_create_post_media").on("change", function (e) {
    var files = e.target.files;
    var imagePreviewContainer = jQuery(".image-preview-container");
    imagePreviewContainer.empty();
    filesArray = Array.from(files);

    for (var i = 0; i < files.length; i++) {
      var file = files[i];
      var fileType = file.type.split("/")[0];
      var reader = new FileReader();

      reader.onload = function (e) {
        var previewElement, clearIcon;

        if (fileType === "image") {
          previewElement = document.createElement("img");
          previewElement.setAttribute("class", "spitout-feed-img-prev");
          previewElement.setAttribute("src", e.target.result);
        } else if (fileType === "video") {
          previewElement = document.createElement("video");
          previewElement.setAttribute("controls", true);
          previewElement.setAttribute("width", "210");
          previewElement.setAttribute("height", "140");
          previewElement.src = URL.createObjectURL(file);
        }

        clearIcon = document.createElement("span");
        clearIcon.setAttribute("class", "clear-image-icon");
        clearIcon.innerHTML = '<i class="bi bi-x-circle"></i>';
        clearIcon.addEventListener("click", function () {
          jQuery(previewElement).remove();
          jQuery(clearIcon).remove();
          filesArray = filesArray.filter((f) => f !== file);
          if (imagePreviewContainer.find("img, video").length === 0) {
            imagePreviewContainer.css("display", "none");
          }
          if (fileType === "video") {
            URL.revokeObjectURL(previewElement.src);
          }
        });

        var fileContainer = document.createElement("div");
        fileContainer.setAttribute("class", "so-clear-image-icon-wrapper");
        fileContainer.appendChild(previewElement);
        fileContainer.appendChild(clearIcon);
        imagePreviewContainer.append(fileContainer);
      };

      if (fileType === "image") {
        reader.readAsDataURL(file);
      } else if (fileType === "video") {
        reader.readAsDataURL(file);
      }
    }

    if (files.length > 0) {
      imagePreviewContainer.css("display", "flex");
    } else {
      imagePreviewContainer.css("display", "none");
    }
  });

  /**
   * Handle the click event for the "Create Comment" button
   *
   * @param {Event} e The event object
   */

  // Global variables to keep track of the request count and timestamp
  let commentRequestCount = 0;
  let commentLastRequestTimestamp = 0;

  // Time window in milliseconds (e.g., 10 seconds)
  const commentTimeWindow = 10000;
  jQuery(document).on("click", ".so-create-comment", function (e) {
    e.preventDefault(); // Prevent default form submission behavior
    const postId = jQuery(this).data("post-id"); // Get the post ID
    jQuery(".so-commentbox-type").val(""); // Clear the comment type field

    // Get the comment content from the textarea
    var commentContent = jQuery(this)
      .siblings(".so-comment-content")
      .val()
      .replace(/\n/g, "<br>"); // Replace line breaks with HTML line breaks

    jQuery(this).siblings(".so-comment-content").val(""); // Clear the comment textarea
    // Check if comment content is empty
    if (!commentContent.trim()) {
      // Trim any whitespace
      console.error("Comment content is empty. Please enter a comment.");
      return; // Do not proceed with the AJAX request
    }
    var placeholderComment = jQuery(".so_placeholder_comment_template").html();

    // Create a new element for the placeholder comment
    var $newPlaceholderComment = jQuery(placeholderComment);
    $newPlaceholderComment
      .find(".so-placeholder-comment-content")
      .text(commentContent);

    // Append the new placeholder comment to the .so-dummy-commentbox element
    jQuery(".so-dummy-commentbox-" + postId).prepend($newPlaceholderComment);

    // Check if the request exceeds the rate limit
    const currentTimestamp = Date.now();
    const timeDifference = currentTimestamp - commentLastRequestTimestamp;

    if (timeDifference < commentTimeWindow && commentRequestCount >= 5) {
      // Display a warning message on the placeholder comment
      $newPlaceholderComment
        .find(".so-delete-comment-status")
        .html(
          "<span>You have submitted too many requests. Please try again later.</span>"
        );
      return;
    }

    // Reset the request count and timestamp if the time window has elapsed
    if (timeDifference >= commentTimeWindow) {
      commentRequestCount = 0;
      commentLastRequestTimestamp = currentTimestamp;
    }

    commentRequestCount++;
    commentLastRequestTimestamp = currentTimestamp;

    // AJAX request to send the form data to the server
    jQuery.ajax({
      url: spit_ajax.ajax_url, // WordPress AJAX URL
      type: "POST",
      data: {
        action: "spitout_save_comment", // AJAX action to handle the request in PHP
        commentContent: commentContent, // Pass the comment content as a parameter
        post_id: postId,
      },
      success: function (response) {
        // Remove the placeholder comment
        $newPlaceholderComment.remove();
        // Append the actual comment content to the .so-dummy-commentbox element
        jQuery(".so-dummy-commentbox-" + postId).prepend(response);
      },
      error: function () {
        // Error handling the AJAX request
        console.error("AJAX error");
      },
    });
  });

  // delete the comment delete
  jQuery(document).on("click", ".spitout-delete-comment-btn", function (e) {
    // e.preventDefault(); // Prevent default form submission behavior
    var commentID = jQuery(this).attr("data-comment-id");

    jQuery(".modal-backdrop").remove();
    jQuery("body").removeClass("modal-open");
    jQuery(".so-close-on-action").modal("hide"); // Hide the modal
    jQuery(".spitout-comment-id-" + commentID).remove();

    // Make an AJAX request to the server
    jQuery.ajax({
      type: "POST",
      url: spit_ajax.ajax_url, // Use the WordPress AJAX URL
      data: {
        action: "spitout_delete_comment_action", // The server-side action to execute
        comment_id: commentID, // Send the comment ID to be deleted
      },
      success: function (response) {
        // Handle success response here
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Handle any AJAX errors here
        console.error("AJAX Error: " + errorThrown);
      },
    });
  });

  jQuery(document).on("click", ".so-delete-comment", function (e) {
    e.preventDefault();
    // Get the seller ID from the data-seller-id attribute
    var commentID = jQuery(this).attr("data-comment-id");
    var postID = jQuery(this).attr("data-post-id");

    // Set the seller ID as the data-user-id attribute in the modal
    jQuery(".so-feed-delete-comment-modal")
      .find(".spitout-delete-comment-btn")
      .attr("data-comment-id", commentID);
    jQuery(".so-feed-delete-comment-modal")
      .find(".spitout-delete-comment-btn")
      .attr("data-post-id", postID);

    // Display the modal
    jQuery(".so-feed-delete-comment-modal").modal("show");
  });

  // Function to handle the click event on the delete post button
  jQuery("body").on("click", ".delete-post-buttonn", function (e) {
    e.preventDefault();
    var postID = jQuery(this).attr("data-post-id");

    // Manually remove the modal backdrop
    jQuery(".modal-backdrop").remove();
    jQuery("body").removeClass("modal-open");
    jQuery(".so-close-on-action").modal("hide"); // Hide the modal

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_delete_post_ajax",
        post_id: postID,
      },

      complete: function () {
        // Display the loader message after the AJAX request is processed
        jQuery(".spitout-post-" + postID).html(
          "<div class='so-error-msg spitout-feed-post-message-content so-feed-new-container so-post-hidden-container'>Post deleted successfully.<i class='bi bi-trash-fill'></i></div>"
        ); // Remove

        /* The above code is using JavaScript to remove an element with a specific class name that is dynamically generated based on the `postID` variable.  */
        setTimeout(function () {
          jQuery(".spitout-post-" + postID).remove();
        }, 5000);
      },

      success: function (response) {
        var data = JSON.parse(response); // Parse the JSON response
        if (data.status === "success") {
          // console.log(data.message); // Log the success message
        } else if (data.status === "error") {
          console.error(data.message); // Log the error message
        }
      },
    });
  });

  // Function to handle the click event on the delete post button
  jQuery("body").on("click", ".so-hide-this-post", function (e) {
    e.preventDefault();
    var postID = jQuery(this).data("post-id");
    var $this = jQuery(this); // Cache the jQuery object for performance
    // Check if the AJAX request has already been made for this element
    if ($this.data("requestRunning")) {
      return; // Exit if the request is already running
    }

    // Set a flag to indicate that the AJAX request is running
    $this.data("requestRunning", true);

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_hide_post_ajax",
        post_id: postID,
      },
      success: function (response) {
        if (response.success) {
          // Manually remove the modal backdrop
          jQuery(".modal-backdrop").remove();
          jQuery("body").removeClass("modal-open");
          jQuery(".so-feed-options-modal-box").modal("hide"); // Hide the modal
          var postElement = jQuery(".spitout-post-" + postID);
          postElement.css("display", "none");
          postElement.after(
            '<div class="so-feed-new-container container spitout-feed-card-wrapper so-post-hidden-container so-post-status-' +
              postID +
              '"> <div class="spitout-post-status-message"><i class="bi bi-x-circle"></i>This post has been hidden. <div class="spitout-restore-post" data-post-id="' +
              postID +
              '"><i class="bi bi-arrow-counterclockwise"></i></div></div></div>'
          );
          // jQuery(".spitout-post-" + postID).html(' <div class="spitout-post-status-message">Post removed. <div class="spitout-restore-post" data-post-id="' + postID + '"> Undo ? </div></div>');
        } else {
          // The request resulted in an error
          console.error(response.data.message); // logs "Data not saved"
        }
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // For example, if there was a network error, or if the request was blocked by the browser
        console.error("AJAX request failed:", textStatus, errorThrown);
      },
      complete: function () {
        // Reset the flag when the AJAX request is complete
        $this.data("requestRunning", false);
      },
    });
  });

  jQuery("body").on("click", ".spitout-restore-post", function (e) {
    e.preventDefault();
    var postID = jQuery(this).data("post-id");
    var $button = jQuery(this); // Cache the button for later use

    // Disable pointer events on the button
    $button.css("pointer-events", "none");

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_unhide_post_ajax",
        post_id: postID,
      },
      success: function (response) {
        var postElement = jQuery(".spitout-post-" + postID);
        postElement.css("display", "block");
        jQuery(".so-post-status-" + postID).hide();
      },
      complete: function () {
        // Re-enable pointer events on the button
        $button.css("pointer-events", "");
      },
    });
  });

  jQuery("body").on("click", ".so-hide-this-seller", function (e) {
    // Get the seller ID from the data-seller-id attribute
    var sellerId = jQuery(this).data("seller-id");
    // Set the seller ID as the data-user-id attribute in the modal
    jQuery(".so-feed-hide-seller-modal")
      .find(".hide-seller-btn")
      .attr("data-user-id", sellerId);
    // Display the modal
    jQuery(".so-feed-hide-seller-modal").modal("show");
  });

  jQuery("body").on("click", ".hide-seller-btn", function (e) {
    e.preventDefault();
    var sellerID = jQuery(this).data("user-id");
    var $button = jQuery(this); // Cache the button for later use

    // Disable pointer events on the button
    $button.css("pointer-events", "none");
    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_hide_seller_ajax",
        seller_id: sellerID,
      },
      success: function (response) {
        // Manually remove the modal backdrop
        jQuery(".modal-backdrop").remove();
        jQuery("body").removeClass("modal-open");
        jQuery(".so-feed-hide-seller-modal").modal("hide"); // Hide the modal
        jQuery(".spitout-uid-" + sellerID).hide();
      },
      complete: function () {
        // Re-enable pointer events on the button
        $button.css("pointer-events", "");
      },
    });
  });

  jQuery(".so-seller-open-modal").click(function () {
    var imgSrc = jQuery(this).data("high-img");
    jQuery("#spitout_modal_image_src").attr("src", imgSrc);
    jQuery("#SpitoutSellerImageModal").modal("show");
  });

  jQuery(".so-feed-uploaded-img .close").click(function () {
    jQuery(this).siblings("img, video").attr("src", "");
    jQuery("#edited-post-image").val("");
  });

  jQuery("body").on("click", ".so-feed-post-edit-submit-btn", function (e) {
    // console.log("clicked");
    e.preventDefault();

    // Gather text content
    var postContent = jQuery("#edited-post-content").val();
    // Prepare form data
    var formData = new FormData();
    formData.append("action", "spitout_update_post"); // Add an AJAX action
    formData.append("post_content", postContent); // Add the text content

    // Append the file
    var fileInput = document.getElementById("edited-post-image");
    if (fileInput.files[0]) {
      formData.append("edited-post-image", fileInput.files[0]);
    }

    // Append the post id
    var postId = jQuery(this).data("post-id");
    formData.append("post_id", postId);

    // AJAX call
    jQuery.ajax({
      url: spit_ajax.ajax_url, // WordPress AJAX URL
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        // Handle success response
        jQuery.ajax({
          type: "GET",
          url: spit_ajax.ajax_url,
          data: {
            action: "so_reload_shortcode",
          },
          success: function (response) {
            jQuery(".so-close-on-action").modal("hide"); // Hide the modal
            jQuery(".modal-backdrop").remove();
            jQuery("body").removeClass("modal-open");
            jQuery(".spitout-feed-new-container-shortcode-wrapper").html(
              response
            );
          },
        });
      },
      error: function (xhr, status, error) {
        // Handle error response
        //console.log(error); // You can customize this part
      },
    });
  });

  // JavaScript code to handle double-click on the image for liking

  jQuery("body").on("dblclick", ".so-feed-uploaded-img", function () {
    const postId = jQuery(".so-feed-uploaded-img img").attr("data-post-id");
    const userId = jQuery(".so-feed-uploaded-img img").attr("data-user-id");
    // Find the elements in the correct context
    const parentElement = jQuery(this).next(".spitout-like-btn-wrapper");
    const heartIcon = parentElement.find(".spitoutlikeBtn i");
    const likeCountElement = parentElement.find(".spitout-likes-count");

    // console.log("the userid is " + userId + " and the post id is " + postId);
    /* The above code is using jQuery to find all the `<i>` elements within an empty jQuery object. */
    var heart = jQuery(this).find("i");
    heart.css("display", "block");
    // heart.fadeIn("slow");

    // setTimeout(function () {
    //   heart.fadeOut("slow");
    // }, 6000);
    heart.animate(
      {
        opacity: 1,
        // top: '-=50',
      },
      500,
      function () {
        setTimeout(function () {
          heart.animate(
            {
              opacity: 0,
              // top: '-=50',
            },
            500,
            function () {
              heart.css("display", "none");
              // heart.css('top', '+=100');
            }
          );
        }, 500);
      }
    );

    var action = "like_action";
    // Send an AJAX request to update the likes count
    jQuery.ajax({
      type: "POST",
      url: spit_ajax.ajax_url, // Assuming that 'ajaxurl' is defined globally by WordPress
      data: {
        action: action,
        post_id: postId,
        user_id: userId,
      },
      success: function (response) {
        const result = JSON.parse(response);
        const likeCount = result.result;

        if (heartIcon.hasClass("bi-heart-fill")) {
          // User has already liked the post, so unlike it
          heartIcon.removeClass("bi-heart-fill");
          heartIcon.addClass("bi-heart");
          jQuery(".spitoutlikeBtn").addClass("so-notify");
          // sucess message on frontend
          // jQuery(".spitout-likes-feedback-" + postId).text(
          //   "You unliked the post"
          // );
        } else {
          // User has not liked the post, so like it
          heartIcon.removeClass("bi-heart");
          heartIcon.addClass("bi-heart-fill");
          // sucess message on frontend
          // jQuery(".spitout-likes-feedback-" + postId).text("You liked the post");
          jQuery(".spitoutlikeBtn").removeClass("so-notify");
        }
        // Update the like count
        if (likeCount === 0) {
          likeCountElement.text("");
        } else if (likeCount === 1) {
          likeCountElement.text(likeCount + " like");
        } else {
          likeCountElement.text(likeCount + " likes");
        }
        // Handle the response if needed (update the UI)
        // For example, update the likes count display
        // var likesCountElement = jQuery('.likes-count'); // Update this selector accordingly
        // likesCountElement.text(response); // Update the likes count based on the response
      },
    });
  });

  //Function to create notifications
  jQuery("body").on("click", ".so-notify", function (e) {
    var sender = jQuery(this).data("sender");
    var receiver = jQuery(this).data("receiver");
    var notificationType = jQuery(this).data("notify");
    var post_id = jQuery(this).data("post-id");

    if (typeof receiver === "undefined" || receiver === null) {
      receiver = 0;
    }

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_notification",
        so_sender: sender,
        so_receiver: receiver,
        so_notification_type: notificationType,
        postID: post_id,
      },
    });
  });

  // jQuery(document).on('click', ".so-notify", function () {
  // jQuery(document).on('click', ".so-notify", function () {
  //   var sender = jQuery(this).data("sender");
  //   var receiver = jQuery(this).data("receiver");
  //   var notificationType = jQuery(this).data("notify");

  //   if (typeof receiver === "undefined" || receiver === null) {
  //     receiver = 0;
  //   }

  //   jQuery.ajax({
  //     url: spit_ajax.ajax_url,
  //     type: "POST",
  //     data: {
  //       action: "so_notification",
  //       so_sender: sender,
  //       so_receiver: receiver,
  //       so_notification_type: notificationType,
  //     }
  //   });
  // });

  // if (typeof receiver === "undefined" || receiver === null) {
  //   receiver = 0;
  // }

  // jQuery.ajax({
  //   url: spit_ajax.ajax_url,
  //   type: "POST",
  //   data: {
  //     action: "so_notification",
  //     so_sender: sender,
  //     so_receiver: receiver,
  //     so_notification_type: notificationType,
  //   }
  // });
  // });

  // hides the modal when the user opens other modal
  jQuery(".so-close-on-action").on("show.bs.modal", function (e) {
    jQuery(".so-feed-options-modal-box").modal("hide");
  });

  //Pull all notification links
  var notificationLinks = document.querySelectorAll(".so-notification-link");

  // Add click event listener to each notification link
  notificationLinks.forEach(function (link) {
    link.addEventListener("tap click", function (event) {
      event.preventDefault(); // Prevent default link behavior

      var notificationId = link.getAttribute("data-notification-id");
      var notificationUrl = link.getAttribute("href");

      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_notification_update",
          ntfy_action: "mark_as_seen",
          notification_id: notificationId,
        },
        success: function (response) {
          if (response.status == "ind_success") {
            window.location.href = notificationUrl;
          }
        },
      });
    });
  });

  //  detele user action
  // Function to handle the click event on the delete user
  jQuery("body").on("click", ".spitout-delete-user-btn", function (e) {
    e.preventDefault();
    var userID = jQuery(this).attr("data-user-id");

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_delete_user",
        user_id: userID,
      },
      success: function (response) {
        // window.open(response);
        window.location.href = response;
      },
    });
  });

  //by vileroze starts==============

  /**
   * The function `animateAndRemove` animates and removes elements selected by the given selector,
   * either individually or as a group.
   * @param selector - Valid CSS selector
   * name, an ID, or an element type.
   * @param single - A boolean value indicating whether the animation and removal should be applied to
   * a single element or multiple elements.
   */
  function animateAndRemove(selector, single) {
    if (single) {
      selector.animate(
        {
          opacity: 0,
          left: "-100px",
        },
        {
          duration: 500,
          // easing: "easeInOut",
          complete: function () {
            jQuery(this).remove();
          },
        }
      );
    } else {
      // console.log("multiple");

      selector.each(function (index) {
        var animationDelay = 200;

        var $this = jQuery(this);

        $this.delay(index * animationDelay).animate(
          {
            opacity: 0,
            left: "-100px",
          },
          {
            duration: 500,
            // easing: "easeInOut",
            complete: function () {
              jQuery(this).remove();
            },
          }
        );
      });
    }
  }

  //stop the header notification from hiding when clicking on an element inside the header
  jQuery(document).on("click", ".nf-dropdown", function (e) {
    e.stopPropagation();
  });

  //all notification actions
  jQuery("body").on("click", ".notification-action", function (e) {
    if (jQuery(this).attr("data-action") == "mark-as-seen") {
      //mark all notifications as seen

      jQuery(this).css("pointer-events", "none");

      //show loader
      jQuery(".seen-ntfy-loader").show();
      jQuery(".seen-ntfy-icon").hide();

      //animate and remove notifications from the header notification
      animateAndRemove(jQuery(".header-notifications li"), false);

      //hide the view all notifications link in the header notification
      jQuery(".nf-all").hide();

      //update both the notification counts
      jQuery("#ntfy-count").html(0);
      jQuery(".notification-count").html(0);

      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_notification_update",
          ntfy_action: "mark_all_as_seen",
        },
        success: function (response) {
          if (response.status == "all_success") {
            jQuery(this).css("pointer-events", "");
            jQuery(".seen-ntfy-loader").hide();
            jQuery(".seen-ntfy-icon").show();

            jQuery(".ntf-single-detail-row").removeClass("so-unseen");
            jQuery(".ntf-single-detail-row").addClass("so-seen");
          }
        },
      });
    } else if (jQuery(this).attr("data-action") == "delete") {
      //delete all notifications

      jQuery(this).css("pointer-events", "none");

      //for the notifciation page, shows the loader
      jQuery(".delete-ntfy-loader").show();
      jQuery(".delete-ntfy-icon").hide();

      //hide the view all notifications link in the header notification
      jQuery(".nf-all").hide();

      //update both the notification counts
      jQuery("#ntfy-count").html(0);
      jQuery(".notification-count").html(0);

      //animate and remove notifications from the header notification
      animateAndRemove(jQuery(".header-notifications li"), false);

      //animate and remove notifications from notifications page
      if (
        jQuery(".so-notification-page .container .so-notification-link")
          .length != 0
      ) {
        animateAndRemove(
          jQuery(".so-notification-page .container .so-notification-link"),
          false
        );
      }

      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_notification_update",
          ntfy_action: "delete",
        },
        success: function (response) {
          if (response.status == "all_deleted") {
            jQuery(this).css("pointer-events", "");
            jQuery(".delete-ntfy-loader").hide();
            jQuery(".delete-ntfy-icon").show();
          }
        },
      });
    } else if (jQuery(this).attr("data-action") == "ind-delete") {
      //delete individual notitification
      var curr_ntfy_id = jQuery(this).attr("data-notification-id");

      //animate and remove the specific notification element
      animateAndRemove(jQuery(this).closest("li"), true);

      //update both the notification counts
      jQuery("#ntfy-count").html(parseInt(jQuery("#ntfy-count").text()) - 1);
      jQuery(".notification-count").html(
        parseInt(jQuery(".notification-count").text()) - 1
      );

      //if in the notification page, animate and delete the item from the notification page as well
      if (
        jQuery(".so-notification-page .container .so-notification-link")
          .length > 0
      ) {
        animateAndRemove(
          jQuery(
            '.so-notification-link[data-notification-id="' + curr_ntfy_id + '"]'
          ),
          true
        );
      }

      //remove specific notification
      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_notification_update",
          ntfy_action: "ind-delete",
          notification_id: curr_ntfy_id,
        },
        success: function (response) {
          if (response.status == "ind_deleted") {
            jQuery(this).css("pointer-events", "");
          }
        },
      });
    }
  });

  //by vileroze ends==============

  // modal box options in feed using ajax start
  jQuery("body").on("click", "#spitoutFeedModalBox", function (event) {
    var $this = jQuery(this); // Store the reference to 'this'
    $this.css("pointer-events", "none");
    jQuery(".so-feed-options-loader-wrapper").css("display", "block");
    event.preventDefault(); // Prevent the default action
    var postId = $this.attr("data-post-id"); // Get the URL from the data attribute
    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_feed_modal_box",
        postId: postId,
      },

      success: function (response) {
        $this.css("pointer-events", "auto");
        $this.css("cursor", "pointer");
        jQuery(".so-feed-options-loader-wrapper").css("display", "none");
        jQuery("#spitoutmodalboxDisplay").html(response);
        jQuery("#soModalBoxDisplay").modal("show");
      },
    });
  });

  // modal box for delete  . delete modal
  jQuery(document).on("click", ".so-delete-list-item-modalbox", function (e) {
    e.preventDefault();
    // Get the seller ID from the data-seller-id attribute
    var postID = jQuery(this).attr("data-post-id");

    // Set the seller ID as the data-user-id attribute in the modal
    // jQuery('.so-feed-delete-comment-modal').find('.spitout-delete-comment-btn').attr('data-comment-id', commentID);
    jQuery(".so-feed-delete-modal-box")
      .find(".delete-post-buttonn")
      .attr("data-post-id", postID);

    // Display the modal
    jQuery(".so-feed-delete-modal-box").modal("show");
  });

  jQuery("body").on("click", "#spitoutEditModalBox", function (event) {
    jQuery("#soModalBoxDisplay").modal("hide");
    jQuery(".so-feed-options-loader-wrapper").css("display", "block");
    event.preventDefault(); // Prevent the default action
    var postId = jQuery(this).attr("data-post-id"); // Get the URL from the data attribute
    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_edit_modal_box",
        postId: postId,
      },

      success: function (response) {
        jQuery(".so-feed-options-loader-wrapper").css("display", "none");
        // jQuery("#spitoutmodalboxDisplay").text('');
        jQuery("#spitouteditmodalboxdisplay").html(response);
        jQuery("#soEditModalBoxDisplay").modal("show");
      },
    });
  });

  // modal box close

  // jQuery('#so_create_post_media').on('change', function (event) {
  //   const file = event.target.files[0];

  //   if (file) {
  //     const reader = new FileReader();

  //     reader.onload = function (e) {
  //       const img = jQuery('<img>').attr('src', e.target.result).css('max-width', '100%');

  //       // Append the image preview inside the text area
  //       jQuery('#post-content').append(img);
  //     };

  //     reader.readAsDataURL(file);
  //   }
  // });

  // var followersCountText = jQuery('.so-user-followers-count').text();
  // // Convert the text to a numeric value
  // var followersCount = parseInt(followersCountText);
  // // Check if followersCount is 0 before opening the modal
  // console.log(followersCount);
  // if (followersCount === 0) {
  //   console.log('No followers');
  //   // Disable modal triggering
  //   jQuery('.so-user-followers-wrapper').removeAttr('data-toggle data-target');
  // } else {
  //   jQuery('.so-user-followers-wrapper').attr('data-toggle', 'modal').attr('data-target', '#spitoutfollowerslist');
  // }

  var ajaxInProgress = false;

  // Function to set the ajaxInProgress flag
  function setAjaxInProgress(value) {
    ajaxInProgress = value;
  }

  // Function to handle the beforeunload event
  function handleBeforeUnload(event) {
    if (ajaxInProgress) {
      event.returnValue = "Are you sure you want to leave?";
    }
  }
  // Attach the event listener
  window.addEventListener("beforeunload", handleBeforeUnload);
});
jQuery(document).on("click", ".spitoutlikeBtn", function (event) {
  // jQuery("body").on("click", ".spitoutlikeBtn", function (event) {
  const postId = jQuery(this).attr("data-post-id");
  const userId = jQuery(this).attr("data-user-id");
  const likeCountElement = jQuery(this).siblings(".spitout-likes-count");
  const heartIcon = jQuery(this).find("i");
  jQuery(this).css("pointer-events", "none");

  jQuery.ajax({
    type: "POST",
    url: spit_ajax.ajax_url,
    data: {
      post_id: postId,
      user_id: userId,
      action: "like_action",
    },
    success: (response) => {
      const result = JSON.parse(response);
      const likeCount = result.result;
      // console.log(postId);
      // Toggle like/unlike action based on the current state
      if (heartIcon.hasClass("bi-heart-fill")) {
        // User has already liked the post, so unlike it
        heartIcon.removeClass("bi-heart-fill");
        heartIcon.addClass("bi-heart");
        jQuery(".spitoutlikeBtn").addClass("so-notify");
      } else {
        // User has not liked the post, so like it
        heartIcon.removeClass("bi-heart");
        heartIcon.addClass("bi-heart-fill");
        // sucess message on frontend
        jQuery(".spitoutlikeBtn").removeClass("so-notify");
      }

      // Update the like count
      if (likeCount === 0) {
        likeCountElement.text("");
      } else if (likeCount === 1) {
        likeCountElement.text(likeCount + " like");
      } else {
        likeCountElement.text(likeCount + " likes");
      }

      jQuery(".spitoutlikeBtn").css("pointer-events", "auto");
      jQuery(".spitoutlikeBtn").css("cursor", "pointer");
    },
    error: (xhr, errmsg, err) => {
      console.error(xhr, errmsg, err);
    },
  });
});

jQuery(document).on("click", ".spitoutCommentlikeBtn", function (e) {
  e.preventDefault();
  const commentID = jQuery(this).attr("data-comment-id");
  const userId = jQuery(this).attr("data-user-id");
  const likeCountElement = jQuery(".so-comment-id-" + commentID);
  const heartIcon = jQuery(this).find("i");
  jQuery(this).css("pointer-events", "none");

  jQuery.ajax({
    type: "POST",
    url: spit_ajax.ajax_url,
    data: {
      comment_id: commentID,
      user_id: userId,
      action: "spitout_comment_action",
    },
    success: (response) => {
      const result = JSON.parse(response);
      const likeCount = result.result;
      // Toggle like/unlike action based on the current state
      if (heartIcon.hasClass("bi-heart-fill")) {
        // User has already liked the post, so unlike it
        heartIcon.removeClass("bi-heart-fill");
        heartIcon.addClass("bi-heart");
      } else {
        // User has not liked the post, so like it
        heartIcon.removeClass("bi-heart");
        heartIcon.addClass("bi-heart-fill");
      }

      likeCountElement.css("display", "block");
      // Update the like count
      if (likeCount === 0) {
        likeCountElement.text("");
      } else if (likeCount === 1) {
        likeCountElement.text(likeCount + " like");
      } else {
        likeCountElement.text(likeCount + " likes");
      }

      jQuery(".spitoutCommentlikeBtn").css("pointer-events", "auto");
      jQuery(".spitoutCommentlikeBtn").css("cursor", "pointer");
    },
    error: (xhr, errmsg, err) => {
      console.error(xhr, errmsg, err);
    },
  });
});

// this js opens the modal box when clicked the total number
jQuery(document).on("click", ".spitout-likes-count", function (event) {
  // jQuery("body").on("click", ".spitout-likes-count", function (event) {
  event.preventDefault(); // Prevent the default action

  var postId = jQuery(this).attr("data-post-id"); // Get the URL from the data attribute
  var commentId = jQuery(this).attr("data-comment-id"); // Get the comment ID from data attribute

  jQuery(this).css("pointer-events", "none");

  if (postId) {
    // If post ID exists, it's a post
    var data = {
      action: "spitout_view_likes_list",
      postId: postId,
    };
  } else if (commentId) {
    // If comment ID exists, it's a comment
    var data = {
      action: "spitout_view_likes_list",
      commentId: commentId,
    };
  } else {
    // Handle the case where neither post ID nor comment ID is provided
    console.error("Invalid request post ID or comment ID empty");
    return;
  }

  jQuery.ajax({
    url: spit_ajax.ajax_url,
    type: "POST",
    data: data,
    success: function (response) {
      jQuery("#spitoutlikesmodalboxdisplay").html(response);
      jQuery("#spitoutlikeslist").modal("show");

      // Set the cursor property for the specific comment element
      if (commentId) {
        jQuery(".so-comment-id-" + commentId).css("pointer-events", "auto");
        jQuery(".so-comment-id-" + commentId).css("cursor", "pointer");
      } else {
        jQuery(".spitout-likes-count").css("pointer-events", "auto");
        jQuery(".spitout-likes-count").css("cursor", "pointer");
      }
    },
  });
});

//=========seller profile page starts===============

//send message request to seller from seller profile page
jQuery(document).on("click", ".search-user-action-btn", function () {
  let currID = (receiver_id = jQuery(this).attr("data-uid"));
  let sender_id = jQuery(this).attr("data-curr-uid");
  let SiteUrl = jQuery(".spitout-site-url").val();

  if (jQuery(this).attr("data-page") == "profile") {
    if (jQuery(this).attr("data-action") == "request") {
      jQuery("#req-sent-notice").show();
      setTimeout(function () {
        jQuery("#req-sent-notice").hide();
      }, 3500);

      //change the icon
      jQuery(this).removeClass("bi-send-plus-fill");
      jQuery(this).addClass("bi-send-check-fill");

      //update the action
      jQuery(this).attr("data-action", "requested");

      //set pointer events to none
      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "cpmm_send_msg_request_from_seller_profile",
          user_id: currID,
        },
        success: function (response) {
          jQuery.ajax({
            url: spit_ajax.ajax_url,
            type: "POST",
            data: {
              action: "so_notification",
              so_sender: sender_id,
              so_receiver: receiver_id,
              so_notification_type: "msg-request",
            },
            success: function (response) {
              jQuery(this).css("pointer-events", "auto");
              window.location.reload();
            },
          });
        },
      });
    } else if (jQuery(this).attr("data-action") == "msg") {
      // opens msg page with respect to user
      window.open(SiteUrl + "/chat/?uid=" + currID, "_blank");
    } else if (jQuery(this).attr("data-action") == "requested") {
      jQuery("#req-sent-notice").show();
      setTimeout(function () {
        jQuery("#req-sent-notice").hide();
      }, 3500);
    }
  }
});

//=========seller profile page ends===============

jQuery(document).ready(function () {
  jQuery("#country")
    .off("change")
    .on("change", function () {
      var selectedCountry = jQuery(this).val();
      // Make an AJAX request to fetch the states based on the selected country.
      jQuery.ajax({
        url: spit_ajax.ajax_url, // The URL to your WordPress AJAX endpoint.
        type: "POST",
        data: {
          action: "get_states", // Create an AJAX action hook for this.
          country: selectedCountry,
        },
        success: function (response) {
          jQuery("#state").html(response); // Update the state dropdown with the retrieved options.
        },
      });
    });
});
//  registration

jQuery("#registrationPassword, #registrationConfirmPassword").on(
  "focusout",
  function () {
    resetErrorMessages();
    var password = jQuery("input[name='pwd']").val();
    var confirmPassword = jQuery("input[name='confirm_password']").val();

    // Check if password is empty
    if (password.trim() === "") {
      appendErrorMessage("pwd", "Password cannot be empty.");
      jQuery(".next-step-register-btn").prop("disabled", true);
      return; // Exit the function if password is empty
    }

    // Check if password is less than 8 characters
    if (password.length < 8) {
      appendErrorMessage("pwd", "Password must be at least 8 characters long.");
      jQuery(".next-step-register-btn").prop("disabled", true);
      return; // Exit the function if password is too short
    }

    // Check if password matches the required pattern
    var pattern = new RegExp(
      "^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*/])[a-zA-Z\\d!@#$%^&*/]{8,}$"
    );
    if (!pattern.test(password)) {
      appendErrorMessage(
        "pwd",
        "Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character."
      );
      jQuery(".next-step-register-btn").prop("disabled", true);
      return; // Exit the function if password does not match the pattern
    }

    // Check if passwords match
    if (password !== confirmPassword) {
      appendErrorMessage("confirm_password", "Passwords do not match.");
      jQuery(".next-step-register-btn").prop("disabled", true);
    } else {
      jQuery(".next-step-register-btn").prop("disabled", false);
    }
  }
);

jQuery(document).on("click", ".multistep-next", function () {
  // Reset error messages before revalidation
  resetErrorMessages();

  // Add your validation logic here
  var isValid = true;

  var fullName = jQuery("input[name='Fname']").val();
  var username = jQuery("input[name='username']").val();
  var email = jQuery("input[name='email']").val();
  var password = jQuery("input[name='pwd']").val();
  var country = jQuery("#country option:selected").text();
  var role = jQuery("#role option:selected").val();
  var state = jQuery("#state option:selected").text();
  var confirmPassword = jQuery("input[name='confirm_password']").val();
  // Validate email format
  var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Calculate the user's age
  var selectedYear = parseInt(jQuery("#year").val());
  var selectedMonth = parseInt(jQuery("#month").val()) - 1;
  var selectedDate = parseInt(jQuery("#day").val());
  const date = new Date();

  const selectedDateObj = new Date(selectedYear, selectedMonth, selectedDate);

  const formattedDate = selectedDateObj.toLocaleDateString("en-GB"); // Output: dd-mm-yyyy

  date.setFullYear(date.getFullYear() - 21);

  if (selectedDateObj >= date) {
    // console.log('You must be at least 21 years old to register.');
    appendErrorMessage(
      "year",
      "You must be at least 21 years old to register."
    );
    isValid = false;
  }

  if (fullName === "") {
    appendErrorMessage("Fname", "Full Name is required.");
    isValid = false;
  }

  if (username === "") {
    appendErrorMessage("username", "Username is required.");
    isValid = false;
  }

  if (email === "") {
    appendErrorMessage("email", "Email is required.");
    isValid = false;
  } else if (!email_pattern.test(email)) {
    appendErrorMessage("email", "Please enter a valid email address.");
    isValid = false;
  }

  if (password === "") {
    appendErrorMessage("pwd", "Password is required.");
    isValid = false;
  }
  if (role === "") {
    var label = jQuery("#role");
    label.after('<div class="so-form-error">Please choose a role.</div>');
    isValid = false;
  }

  var agreeCheckbox = jQuery("#agree");
  if (!agreeCheckbox.prop("checked")) {
    // Display an error message or take any other action
    var label = jQuery("label[for='agree']");
    label.after(
      '<div class="so-form-error">Please agree to the terms of use & privacy policy.</div>'
    );
    isValid = false;
  }
console.log(isValid);
  // If all validation passes, proceed to the next step
  if (isValid) {
    // jQuery.ajax({
    //   type: "POST",
    //   url: spit_ajax.ajax_url,
    //   data: {
    //     action: "spitout_user_registration",
    //     fullName: fullName,
    //     username: username,
    //     email: email,
    //     dob: formattedDate,
    //     accountpassword: password,
    //     confirmPassword: confirmPassword,
    //     country: country,
    //     state: state,
    //     role: role,
    //   },

    //   success: function (response) {
    //     console.log(response);
    //     if (response.status === "success") {
    //       jQuery(".spitout-user-created-message").html(
    //         '<div class="so-success-msg">' + response.message + "</div>"
    //       );
    //     } else {
    //       if (response.type === "username") {
    //         appendErrorMessage(response.type, response.message);
    //       } else if (response.type === "email") {
    //         appendErrorMessage(response.type, response.message);
    //       } else if (response.type === "pwd") {
    //         appendErrorMessage(response.type, response.message);
    //       } else {
    //         // Handle the default condition here
    //         console.error("Unknown response type:", response.type);
    //       }
    //     }

    //     location.reload();
    //   },
    // });
  }
});

// Helper function to append error messages after the input elements
function appendErrorMessage(inputName, message) {
  var inputElement = jQuery("input[name='" + inputName + "']");
  inputElement.after("<div class='so-form-error'>" + message + "</div>");
}

// Helper function to reset error messages
function resetErrorMessages() {
  jQuery(".so-form-error").remove();
}

function checkIfExists(inputField, action) {
  // Reset error messages before revalidation
  resetErrorMessages();

  // Get the entered input
  var enteredInput = jQuery("#" + inputField).val();

  // Make an AJAX request to check if the input already exists
  jQuery.ajax({
    url: spit_ajax.ajax_url, // Use the WordPress AJAX URL
    type: "POST",
    data: {
      action: action, // WordPress AJAX action
      input: enteredInput,
    },
    success: function (response) {
      // Handle the response from the server
      if (response === "exists") {
        appendErrorMessage(
          inputField,
          "This " + inputField + " is already registered."
        );
      }
    },
  });
}

// Attach a blur event to the email input field
jQuery("#email").on("blur", function () {
  checkIfExists("email", "spitout_check_email_exists");
});

// Attach a blur event to the username input field
jQuery("#username").on("blur", function () {
  checkIfExists("username", "spitout_check_username_exists");
});

//========vile myspitout starts=======
jQuery(document).ready(function () {
  //add new product
  var ajax;
  jQuery("#add_new_product_form").submit(function (e) {
    e.preventDefault();

    //disable button and change it to adding...
    jQuery("#add_new_product_form")
      .find("[name='add_new_product']")
      .css("pointer-events", "none");
    jQuery("#add_new_product_form")
      .find("[name='add_new_product']")
      .val("Adding...");

    let product_name = jQuery(this).find("[name='product_name']").val();
    let price = jQuery(this).find("[name='price']").val();
    let additional_info = jQuery(this).find("[name='additional_info']").val();
    let product_icon = jQuery(this).find("[name='product_icon']").val();
    let seller_id = jQuery("#curr-seller-id").val();

    //for uploading image
    var fd = new FormData();
    var file = jQuery(this).find('input[type="file"]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append("action", "so_upload_product_image");

    //show loader
    jQuery(".add-product-loader").show();

    //if there is an image to upload
    if (individual_file) {
      //upload the image
      jQuery.ajax({
        type: "POST",
        url: spit_ajax.ajax_url,
        data: fd,
        contentType: false,
        processData: false,
        success: function (response) {
          var newAttachmentID = response.data;

          //create new product
          jQuery.ajax({
            url: spit_ajax.ajax_url,
            type: "POST",
            data: {
              action: "so_add_new_product",
              product_name: product_name,
              price: price,
              additional_info: additional_info,
              product_icon: product_icon,
              product_image: newAttachmentID,
            },
            success: function (response) {
              //refresh the product list
              jQuery.ajax({
                url: spit_ajax.ajax_url,
                type: "POST",
                data: {
                  action: "so_retrieve_seller_products",
                  seller_id: seller_id,
                },
                success: function (response) {
                  //add new list
                  jQuery("#seller-products tbody").html(response);

                  //reset form
                  jQuery("#add_new_product_form")[0].reset();

                  //hide loader
                  jQuery(".add-product-loader").hide();

                  jQuery("#close-add-product-modal").click();

                  //enable button
                  jQuery("#add_new_product_form")
                    .find("[name='add_new_product']")
                    .css("pointer-events", "");
                  jQuery("#add_new_product_form")
                    .find("[name='add_new_product']")
                    .val("Add Product");
                },
              });
            },
          });
        },
      });
    } else {
      //if no image uploaded
      if (ajax) {
        ajax.abort();
      }

      //create new product
      ajax = jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_add_new_product",
          product_name: product_name,
          price: price,
          additional_info: additional_info,
          product_icon: product_icon,
        },
        success: function (response) {
          //refresh product list
          jQuery.ajax({
            url: spit_ajax.ajax_url,
            type: "POST",
            data: {
              action: "so_retrieve_seller_products",
              seller_id: seller_id,
            },
            success: function (response) {
              jQuery("#seller-products tbody").html(response);

              //reset form
              jQuery("#add_new_product_form")[0].reset();

              //hide loader
              jQuery(".add-product-loader").hide();

              jQuery("#close-add-product-modal").click();

              //enable button
              jQuery("#add_new_product_form")
                .find("[name='add_new_product']")
                .css("pointer-events", "");
              jQuery("#add_new_product_form")
                .find("[name='add_new_product']")
                .val("Add Product");
            },
          });
        },
      });
    }
  });

  //hide or unhide products
  jQuery("body").on("click", ".so-my-spitout-hideBtn", function (event) {
    //jQuery(".so-my-spitout-hideBtn").on("click", function (event) {
    //stop user from spamming
    jQuery(this).css("pointer-events", "none");

    let product_id = jQuery(this).attr("data-product-id");
    let hidden = jQuery(this).html().trim() == "Hide" ? true : false;

    if (hidden) {
      jQuery(this).html("Hiding...");
    } else {
      jQuery(this).html("Unhiding...");
    }

    let that = this;

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_hide_unhide_products",
        product_id: product_id,
      },
      success: function (response) {
        jQuery("#close-add-product-modal").click();

        if (response.success) {
          jQuery(that).css("pointer-events", "auto");

          if (hidden) {
            jQuery(that).html("Unhide");
          } else {
            jQuery(that).html("Hide");
          }
        } else {
          alert(response.data);
        }
      },
    });
  });

  //delete products
  jQuery(document).on("click", ".so-my-spitout-deteteBtn", function (event) {
    jQuery(".delete-product").attr(
      "data-product-id",
      jQuery(this).attr("data-product-id")
    );
  });

  jQuery(".delete-product").on("click", function (event) {
    let product_id = jQuery(this).attr("data-product-id");
    jQuery(".product-row-" + product_id).remove();
    jQuery("#close-delete-product-modal").click();

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_delete_product",
        product_id: product_id,
      },
      success: function (response) {},
    });
  });

  //show product details in the edit modal
  jQuery(document).on("click", ".so-my-spitout-editBtn", function (event) {
    //get data
    let product_id = jQuery(this).attr("data-product-id");
    let product_name = jQuery(this).attr("data-product-name");
    let product_price = jQuery(this).attr("data-product-price");
    let product_info = jQuery(this).attr("data-product-info");
    let product_img = jQuery(this).attr("data-product-img");
    let product_icon = jQuery(this).attr("data-product-icon");

    //populate modal fields
    let editModal = jQuery("#so-my-spitout-editModal");

    editModal.find('[name="product_id"]').val(product_id);
    editModal.find('[name="product_name"]').val(product_name);
    editModal.find('[name="price"]').val(product_price);
    editModal.find('[name="additional_info"]').val(product_info);
    editModal.find(".product_img").attr("src", product_img);
    editModal.find("[name='product_icon']").val(product_icon);

    if (product_img == "") {
      editModal.find(".product_img").hide();
    } else {
      editModal.find(".product_img").show();
    }

    //preselect the icon
    if (product_icon == "jar") {
      jQuery(".btn-selecte").eq(0).click();
      jQuery("#so-editp-a").children("li").eq(2).click();
    } else {
      jQuery(".btn-selecte").eq(0).click();
      jQuery("#so-editp-a").children("li").eq(1).click();
    }
  });

  //edit product
  jQuery(".update_product").on("click", function (event) {
    let seller_id = jQuery("#curr-seller-id").val();

    let editModal = jQuery("#so-my-spitout-editModal");
    let product_id = editModal.find('[name="product_id"]').val();
    let product_name = editModal.find('[name="product_name"]').val();
    let product_price = editModal.find('[name="price"]').val();
    let product_info = editModal.find('[name="additional_info"]').val();
    let product_icon = editModal.find(".btn-selecte").attr("value");

    //show loader hide button
    jQuery(".update_product").hide();
    jQuery(".update-product-loader").show();

    //for uploading image
    let fd = new FormData();
    let file = editModal.find('input[type="file"]');
    let individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append("action", "so_upload_product_image");

    //if there is an image to upload
    if (individual_file) {
      //upload the image
      jQuery.ajax({
        type: "POST",
        url: spit_ajax.ajax_url,
        data: fd,
        contentType: false,
        processData: false,
        success: function (response) {
          let newAttachmentID = response.data;

          // console.log("=====1");

          //edit product
          jQuery.ajax({
            url: spit_ajax.ajax_url,
            type: "POST",
            data: {
              action: "so_edit_product",
              product_id: product_id,
              product_name: product_name,
              product_price: product_price,
              product_info: product_info,
              product_icon: product_icon,
              product_image: newAttachmentID,
            },
            success: function (response) {
              // console.log("=====2");
              //show button hide loader
              jQuery(".update_product").show();
              jQuery(".update-product-loader").hide();

              window.location.href = "";

              //refresh product list
              jQuery.ajax({
                url: spit_ajax.ajax_url,
                type: "POST",
                data: {
                  action: "so_retrieve_seller_products",
                  seller_id: seller_id,
                },
                success: function (response) {
                  jQuery("#seller-products tbody").html(response);
                  jQuery("#close-edit-product-modal").click();
                },
              });
            },
          });
        },
      });
    } else {
      //edit product
      jQuery.ajax({
        url: spit_ajax.ajax_url,
        type: "POST",
        data: {
          action: "so_edit_product",
          product_id: product_id,
          product_name: product_name,
          product_price: product_price,
          product_info: product_info,
          product_icon: product_icon,
        },
        success: function (response) {
          //refresh product list
          jQuery.ajax({
            url: spit_ajax.ajax_url,
            type: "POST",
            data: {
              action: "so_retrieve_seller_products",
              seller_id: seller_id,
            },
            success: function (response) {
              // console.log("🚀 ~ response:", response);
              jQuery("#seller-products tbody").html(response);
              jQuery("#close-edit-product-modal").click();

              //show button hide loader
              jQuery(".update_product").show();
              jQuery(".update-product-loader").hide();
            },
          });
        },
      });
    }
  });
});

//========vile myspitout ends=======

/* submits comment when the user clicks enter and changes to next line when the user clicks shift+enter */
jQuery(document).on("keypress", "textarea.so-comment-content", function (e) {
  // var postID = jQuery(this).data("post-id");
  var textareaValue = jQuery(this).val(); // Get the value of the textarea and remove leading/trailing whitespace

  if (e.key == "Enter") {
    if (e.shiftKey) {
      // Add new line
      jQuery(this).val(jQuery(this).val() + "\n");
    } else if (textareaValue !== "") {
      jQuery(this).next(".so-create-comment").click();
    }
    e.preventDefault();
  }
});

/* prevents user from entering blank comment and add a class "spitout-comment-disabled" if the text area is disabled */
jQuery(document).on("input change", "textarea.so-comment-content", function () {
  var postID = jQuery(this).attr("data-post-id");
  var textareaValue = jQuery(this).val(); // Get the value of the textarea and remove leading/trailing whitespace
  var commentButton = jQuery(".so-create-comment-" + postID);

  if (textareaValue !== "") {
    commentButton.css("display", "block");
    commentButton.removeClass("spitout-comment-disabled");
  } else {
    commentButton.css("display", "none");
    commentButton.addClass("spitout-comment-disabled");
  }
});

//=======send browser notification starts=====

jQuery(document).ready(function () {
  var currentURL = window.location.href;

  if (!currentURL.includes("login") && !currentURL.includes("register")) {
    // Button click event
    jQuery(document).on("click", "#request-permission", function () {
      Notification.requestPermission().then(function (status) {
        if (status === "granted") {
          jQuery("#request-permission").hide();
        } else if (status === "granted") {
          jQuery("#request-permission").show();
        }
      });
    });

    jQuery(document).ready(function () {
      Notification.requestPermission().then(function (status) {
        if (status === "granted") {
          jQuery("#request-permission").hide();
        } else {
          jQuery("#request-permission").show();
        }
      });
    });

    // if (jQuery("body").hasClass("logged-in")) {
    //   setInterval(() => {
    //     let sender_id_list = [];

    //     let last_ntfy_id = jQuery(".header-notifications li a")
    //       .first()
    //       .attr("data-notification-id");

    //     jQuery.ajax({
    //       url: spit_ajax.ajax_url,
    //       type: "POST",
    //       data: {
    //         action: "so_retrieve_new_messages",
    //         limit: 5,
    //         last_ntfy_id: last_ntfy_id,
    //       },

    //       success: function (response) {
    //         if (response.data == "no-new-msg") {
    //           jQuery(".new-msg-icon").hide();
    //           return;
    //         }

    //         jQuery(".new-msg-icon").show();

    //         if (Notification.permission === "granted") {
    //           if (window.location.href.includes("chat")) {
    //             return;
    //           }
    //         }

    //         console.log(response);

    //         response.data.forEach((ntfy) => {
    //           console.log("ppp");
    //           if (!sender_id_list.includes(ntfy.sender_id)) {
    //             sender_id_list.push(ntfy.sender_id);

    //             let notification = new Notification("NEW MESSAGE", {
    //               body: "You have unread messages !!",
    //               icon: jQuery(".so-logo-mobile .navbar-brand img")
    //                 .eq(0)
    //                 .attr("src"),
    //             });

    //             notification.onclick = (event) => {
    //               event.preventDefault();
    //               location.href =
    //                 jQuery(".navbar-brand").attr("href") +
    //                 "chat" +
    //                 "?uid=" +
    //                 ntfy.sender_id;
    //             };
    //           }

    //           //mark the notification as seen
    //           jQuery.ajax({
    //             url: spit_ajax.ajax_url,
    //             type: "POST",
    //             data: {
    //               action: "so_mark_ntfy_as_seen",
    //               ntfy_id: ntfy.id,
    //             },
    //             success: function (response) {},
    //           });
    //         });
    //       },
    //     });
    //   }, 10000);
    // }
  }
});

//=======send browser notification ends=====

// this function is used or loaded when the user clicks the followers tab and than only the content are loaded, it works only in first load
var isFollowersTabLoaded = false; // Flag variable
jQuery(document).on("click", "#pills-nf-following-tab", function (e) {
  e.preventDefault(); // Prevent default form submission behavior

  // If the content has already been loaded, do nothing
  if (isFollowersTabLoaded) {
    return;
  }

  jQuery.ajax({
    url: spit_ajax.ajax_url,
    type: "POST",
    data: {
      action: "so_load_tab_content",
    },
    success: function (response) {
      jQuery(".initital_response_following").html(response);
      // After the content is successfully loaded, set the flag to true
      isFollowersTabLoaded = true;
    },
    error: function (jqXHR, textStatus, errorThrown) {
      // Log the error to the console
      console.error("Error: " + textStatus + ": " + errorThrown);
      // Optionally, display an error message to the user
      jQuery(".initital_response_following").html(
        "<p>An error occurred while loading the content. Please try again.</p>"
      );
    },
  });
});

//let BUYER mark an order as 'completed'
jQuery(document).ready(function () {
  jQuery(".order-marked-as-recieved").on("click tap", function (e) {
    e.preventDefault();

    jQuery(this).hide();

    let order_id = jQuery(this).attr("data-order-id");

    let completed_html =
      `
      <a class="so-ordercard-status-paid completed">
        <p>Completed</p>
      </a>

      <a href="` +
      window.location.origin +
      `/product-review/?order_id=` +
      order_id +
      `" target="_blank" class="so-ordercard-status-paid review_completed">
        <p>Leave a review</p>
      </a>`;

    jQuery(this)
      .closest(".so-ordercard-row-content")
      .find(".so-order-pay-status")
      .html(completed_html);

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_mark_order_as_complete",
        order_id: order_id,
      },
      success: function (response) {},
    });
  });

  jQuery(".order-marked-as-recieved-view-order").on("click tap", function (e) {
    e.preventDefault();

    jQuery(this).hide();

    let order_id = jQuery(this).attr("data-order-id");

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_mark_order_as_complete",
        order_id: order_id,
      },
      success: function (response) {},
    });
  });
});

//seller can only confirm order after entering tracking id
jQuery(document).ready(function () {
  jQuery(".update-tracking-num").submit(function (event) {
    event.preventDefault();
  });

  jQuery(document).on("keyup", ".update-order-tracking_num", function () {
    if (jQuery(this).val().length > 0) {
      // console.log('has');
      jQuery(".confirm-tracking-num").css("pointer-events", "auto");
      // jQuery(".confirm-tracking-num").show();
    } else {
      // console.log('does not');
      jQuery(".confirm-tracking-num").css("pointer-events", "none");
      // jQuery(".confirm-tracking-num").hide();
    }
  });

  jQuery(".confirm-tracking-num").on("click tap", function () {
    jQuery(this).css("pointer-events", "none");
    jQuery(this).html("Confirming...");
    let order_id = jQuery(this)
      .closest("form")
      .find(".update-order-order-id")
      .val();
    let tracking_num = jQuery(this)
      .closest("form")
      .find(".update-order-tracking_num")
      .val();
    let sender_id = jQuery(this).attr("data-sender-id");
    let receiver_id = jQuery(this).attr("data-receiver-id");

    if (tracking_num.length <= 0) {
      jQuery(".update-order-tracking_num").css({
        outline: "2px solid red",
        "outline-offset": "2px",
      });
      return;
    }

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_mark_order_as_shipped",
        order_id: order_id,
        tracking_num: tracking_num,
      },
      success: function (response) {
        jQuery(this).css("pointer-events", "");
        jQuery(this).html("Confirm");
        jQuery.ajax({
          url: spit_ajax.ajax_url,
          type: "POST",
          data: {
            action: "so_notification",
            so_sender: sender_id,
            so_receiver: receiver_id,
            so_notification_type: "order-shipped",
            postID: tracking_num,
          },
          success: function (response) {
            jQuery(this).css("pointer-events", "auto");
            window.location.reload();
          },
          error: function (response) {
            jQuery(this).css("pointer-events", "auto");
          },
        });
      },
    });
  });
});

jQuery(document).ready(function () {
  var currentUrl = window.location.href;
  var queryString = currentUrl.split("?")[1];
  var urlParams = new URLSearchParams(queryString);

  if (urlParams.has("order_id")) {
    var orderIdValue = urlParams.get("order_id");
    var targetSpan = jQuery('span:contains("' + orderIdValue + '")');

    if (targetSpan.length) {
      jQuery("html, body").animate(
        {
          scrollTop: targetSpan.offset().top,
        },
        1000
      );
    }
  }
});

//send notification when buyer leaves a review
jQuery(document).ready(function () {
  // jQuery(document).on("submit", "#review-form", function (e) {
  //   e.preventDefault();

  //   //disable the submit button to prevent spamming
  //   jQuery("#review-form input[type='submit']").attr("disabled", true);

  //   let senderID = jQuery(".data-for-review").attr("data-sender-id");
  //   let receiverID = jQuery(".data-for-review").attr("data-receiver-id");

  //   jQuery.ajax({
  //     url: spit_ajax.ajax_url,
  //     type: "POST",
  //     data: {
  //       action: "so_notification",
  //       so_sender: senderID,
  //       so_receiver: receiverID,
  //       so_notification_type: "seller-review",
  //     },
  //     success: function (response) {
  //       jQuery("#review-form")[0].submit();
  //     },
  //   });
  // });
  jQuery(document).on("click", ".send-new-review-ntf", function (e) {
    jQuery(this).attr("disabled", true);

    let senderID = jQuery(".data-for-review").attr("data-sender-id");
    let receiverID = jQuery(".data-for-review").attr("data-receiver-id");

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "so_notification",
        so_sender: senderID,
        so_receiver: receiverID,
        so_notification_type: "seller-review",
      },
      success: function (response) {
        jQuery(".add-new-review-btn").click();
      },
    });
  });
});

jQuery(document).ready(function () {
  jQuery(document).on("click", ".so-unhide-user-btn", function (e) {
    e.preventDefault();

    var get_user_id = jQuery(this).attr("data-id");

    //remove element
    jQuery(this).closest(".so-likes-list-modal-content").remove();

    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "spitout_hidden_users",
        user_id: get_user_id,
      },
      success: function (response) {
        jQuery(".hidden_user-" + get_user_id).text(response);
        setTimeout(function () {
          jQuery(".hidden_user-" + get_user_id).fadeOut();
        }, 3000);
      },
    });
  });
});

jQuery(document).ready(function ($) {
  if (
    jQuery("body").hasClass("logged-in") &&
    jQuery("body").hasClass("page-template-template-feed")
  ) {
    setInterval(function () {
      // Check if the body has the 'new-posts-available' class inside the interval
      if (!jQuery("body").hasClass("new-posts-available")) {
        $.ajax({
          url: spit_ajax.ajax_url,
          type: "POST",
          data: {
            action: "so_check_new_posts",
          },
          success: function (response) {
            if (response.new_posts) {
              jQuery("body").addClass("new-posts-available");
              jQuery("#new-posts-notice").show();

              //  alert("New posts are available!");
            }
          },
        });
      }
    }, 10000); // 10 seconds in milliseconds
  }
});

jQuery(document).ready(function ($) {
  var loadMoreBtn = $("#load-more-reviews-btn");
  var authorId = loadMoreBtn.data("author-id");
  var offset = loadMoreBtn.data("offset");
  var reviewContainer = $("#review-container");

  loadMoreBtn.on("click", function () {
    $.ajax({
      url: spit_ajax.ajax_url, // Replace with the URL to your AJAX file
      type: "POST",
      data: {
        action: "spitout_load_more_reviews",
        author_id: authorId,
        offset: offset,
      },
      beforeSend: function () {
        loadMoreBtn.prop("disabled", true);
      },
      success: function (response) {
        if (response.trim() === "No more reviews found.") {
          loadMoreBtn.remove(); // Remove the button instantly
        } else {
          $(".load-more-reviews").before(response);
          offset += 2; // Increment the offset by the number of reviews loaded
          loadMoreBtn.data("offset", offset);
          loadMoreBtn.prop("disabled", false);
        }
      },
      error: function (xhr, status, error) {
        console.log("AJAX Error: " + status + " - " + error);
        loadMoreBtn.prop("disabled", false);
      },
    });
  });
});


jQuery(document).ready(function(){
  jQuery('.cat-item').on('click',function(){
    var selected_html = jQuery(this).prop('outerHTML');
    jQuery('.selected-cat').empty();

    // Append the clicked .cat-item HTML to .selected-cat
    jQuery('.selected-cat').append(selected_html);
    console.log(selected_html);
  });
});