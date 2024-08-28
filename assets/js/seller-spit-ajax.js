jQuery(document).ready(function () {
    var selectedIds = [];
    var button_id;
    var button_target;
    var tab;
    var searchValue;
  
    var typingTimer; // Timer identifier
    var doneTypingInterval = 3000; // Time in milliseconds (3 seconds)
  
    jQuery("#pills-tab .nav-link").on("click", function () {
      button_id = jQuery(this).attr("data-button-id");
      button_target = jQuery(this).attr("data-target");
      tab = jQuery(this).attr("data-tab");
      seller_filter(button_id, button_target, tab, selectedIds, searchValue);
    });
  
    jQuery(".category-class").on("change", function () {
      let id = jQuery(this).data("id");
      if (!selectedIds.includes(id)) {
        selectedIds.push(id); // Push only if not already in the array
      } else {
        let index = selectedIds.indexOf(id);
        if (index !== -1) {
          selectedIds.splice(index, 1);
        }
      }
      seller_filter(button_id, button_target, tab, selectedIds, searchValue);
    });
  
    // On keyup, start the countdown
    jQuery("#seller_search").on("keyup", function () {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(function () {
        doneTyping(button_id, button_target, tab, selectedIds);
      }, doneTypingInterval);
    });
  
    // On keydown, clear the countdown
    jQuery("#seller_search").on("keydown", function () {
      clearTimeout(typingTimer);
    });
  
    seller_filter();
  });
  
  function seller_filter(
    button_id = "",
    button_target = "",
    tab = "",
    cat_id = [],
    searchValue = ""
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
  function doneTyping(button_id, button_target, tab, selectedIds, searchValue) {
    searchValue = jQuery("#seller_search").val();
    seller_filter(button_id, button_target, tab, selectedIds, searchValue);
  }
  