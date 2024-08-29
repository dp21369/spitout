jQuery(document).ready(function () {
    var selectedIds = [];
    var button_id;
    var button_target;
    var tab;
    var searchValue;
    var location;
  
    var typingTimer; // Timer identifier
    var doneTypingInterval = 1000; // Time in milliseconds (1.5 seconds)
  
    // runs on tab changes
    jQuery("#pills-tab .nav-link").on("click", function () {
      searchValue = jQuery("#seller_search").val();
      location = jQuery("#location").val();
      button_id = jQuery(this).attr("data-button-id");
      button_target = jQuery(this).attr("data-target");
      tab = jQuery(this).attr("data-tab");
      seller_filter(button_id, button_target, tab, selectedIds, searchValue,location);
    });
  
    //runs on category selected
    jQuery(".category-class").on("change", function () {
      searchValue = jQuery("#seller_search").val();
      location = jQuery("#location").val();
      let id = jQuery(this).data("id");
      if (!selectedIds.includes(id)) {
        selectedIds.push(id); // Push only if not already in the array
      } else {
        let index = selectedIds.indexOf(id);
        if (index !== -1) {
          selectedIds.splice(index, 1);
        }
      }
      seller_filter(button_id, button_target, tab, selectedIds, searchValue,location);
    });
  
    // On keyup, start the countdown
    jQuery("#seller_search").on("keyup", function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(function () {
        doneTyping(button_id, button_target, tab, selectedIds,location);
      }, doneTypingInterval);
    });
  
    // On keydown, clear the countdown
    jQuery("#seller_search").on("keydown", function () {
      clearTimeout(typingTimer);
    });

    //runs on location dropdown changes
    // jQuery('#location').change(function() {
    jQuery(document).on('click','#apply-button',function() {
      location = jQuery("#location").val();
      searchValue = jQuery("#seller_search").val();
      seller_filter(button_id, button_target, tab, selectedIds, searchValue,location);
  });
  
    seller_filter();
  });
  
  function seller_filter(
    button_id = "",
    button_target = "",
    tab = "",
    cat_id = [],
    searchValue = "",
    location = "",
  ) {
    jQuery.ajax({
      url: spit_ajax.ajax_url,
      type: "POST",
      data: {
        action: "load_filtered_sellers",
        button_id: button_id,
        button_target: button_target,
        tab: tab,
        cat_id: cat_id,
        searchValue: searchValue,
        location: location,
      },
      beforeSend: function () {
        jQuery("#new-seller-tab-loader").show();
        jQuery("#seller-pills-tabContent").empty();
      },
      success: function (response) {
        jQuery("#seller-pills-tabContent").html(response);
      },
      complete: function () {
        jQuery("#new-seller-tab-loader").hide();
      },
    });
  }
  
  // User is "finished typing," do something
  function doneTyping(button_id, button_target, tab, selectedIds, searchValue,location) {
    searchValue = jQuery("#seller_search").val();
    location = jQuery("#location").val();
    seller_filter(button_id, button_target, tab, selectedIds, searchValue,location);
  }
  