/**
 * Adds an event listener to the document for touchstart and click events on elements with the class "feed-forYou" or "feed-following".
 * When a.feed-forYou element is clicked or touched, the value of the hidden input element with the id "feed_type" is set to "for_you".
 * When a.feed-following element is clicked or touched, the value of the hidden input element with the id "feed_type" is set to "following".
 */
jQuery(document).ready(function () {
  jQuery(document).on(
    "touchstart click",
    ".feed-forYou, .feed-following",
    function () {
      if (jQuery(this).hasClass("feed-forYou")) {
        jQuery("#feed_type").val("for_you");
        // Do something when a .feed-forYou element is clicked or touched
      } else if (jQuery(this).hasClass("feed-following")) {
        // Do something when a .feed-following element is clicked or touched
        jQuery("#feed_type").val("following");
      }
    }
  );
});

jQuery(function ($) {
  function spitoutLoadMorePostsScrollCallback() {
    var button = $(".cpm_load_more_feed"),
      queryArgs = button.data("args"),
      maxPage = button.data("max-page"),
      currentPage = button.data("current-page"),
      feed_type = button.data("feed-type");

    // Add a flag to check if an AJAX request is in progress
    if (button.data("loading")) {
      return; // Exit the function if a request is already in progress
    }

    // Check if the button is in the viewport
    if (
      button.length > 0 &&
      $(window).scrollTop() + $(window).height() >= button.offset().top
    ) {
      // Set the loading flag to true
      button.data("loading", true);

      $.ajax({
        url: spitout_loadmore_params.ajaxurl, // AJAX handler
        data: {
          action: "spitout_feed_load_more_ajax", // the parameter for admin-ajax.php
          query: queryArgs,
          page: currentPage,
          type: feed_type,
        },
        type: "POST",
        beforeSend: function (xhr) {
          // button.text('Loading...'); // some type of preloader
        },
        success: function (data) {
          currentPage++;
          button.data("current-page", currentPage).before(data);
          if (currentPage == maxPage) {
            button.remove();
            $(".so-all-caught-up").css("display", "block");
          }
        },
        complete: function () {
          // Reset the loading flag when the request is complete
          button.data("loading", false);
        },
      });
    }
  }
  /*
   * Attach scroll event to the window
   */
  $(window).on("scroll", spitoutLoadMorePostsScrollCallback);
});
