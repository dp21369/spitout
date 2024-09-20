jQuery(document).ready(function () {
  var selectedIds = [];
  var button_id;
  var button_target;
  var tab;
  var searchValue;
  var location;

  var selectedMinAge;
  var selectedMaxAge;

  var selectedMinPrice;
  var selectedMaxPrice;
  // var selectedMinAge = jQuery("#age-start").val();
  // var selectedMaxAge = jQuery("#age-end").val();

  // var selectedMinPrice = jQuery("#price-start").val();
  // var selectedMaxPrice = jQuery("#price-end").val();

  var typingTimer; // Timer identifier
  var doneTypingInterval = 500; // Time in milliseconds (1 seconds)

  // runs on tab changes
  jQuery("#pills-tab .nav-link").on("click", function () {
    searchValue = jQuery("#seller_search").val();
    location = jQuery("#location").val();
    button_id = jQuery(this).attr("data-button-id");
    button_target = jQuery(this).attr("data-target");
    tab = jQuery(this).attr("data-tab");

    // selectedMinAge = jQuery("#age-start").val();
    // selectedMaxAge = jQuery("#age-end").val();

    // selectedMinPrice = jQuery("#price-start").val();
    // selectedMaxPrice = jQuery("#price-end").val();
    seller_filter(
      button_id,
      button_target,
      tab,
      selectedIds,
      searchValue,
      location,
      selectedMinAge,
      selectedMaxAge,
      selectedMinPrice,
      selectedMaxPrice
    );
  });

  // jQuery('input.category-class').on('change', function() {
  //   if (jQuery(this).is(':checked')) {
  //     console.log('Checkbox with cat-slug: ' + jQuery(this).data('cat-slug') + ' is now checked.');
  //   } else {
  //     console.log('Checkbox with cat-slug: ' + jQuery(this).data('cat-slug') + ' is now unchecked.');
  //   }
  // });

  //runs on category selected
  jQuery(".category-class").on("change", function () {
    searchValue = jQuery("#seller_search").val();
    location = jQuery("#location").val();
    let id = jQuery(this).data("id");
    if (!selectedIds.includes(id) && jQuery(this).is(":checked")) {
      selectedIds.push(id); // Push only if not already in the array
    } else {
      let index = selectedIds.indexOf(id);
      if (index !== -1) {
        selectedIds.splice(index, 1);
      }
    }

    selectedMinAge = jQuery("#age-start").val();
    selectedMaxAge = jQuery("#age-end").val();

    selectedMinPrice = jQuery("#price-start").val();
    selectedMaxPrice = jQuery("#price-end").val();
    seller_filter(
      button_id,
      button_target,
      tab,
      selectedIds,
      searchValue,
      location,
      selectedMinAge,
      selectedMaxAge,
      selectedMinPrice,
      selectedMaxPrice
    );
  });

  // On keyup, start the countdown
  jQuery("#seller_search").on("keyup", function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(function () {
      doneTyping(
        button_id,
        button_target,
        tab,
        selectedIds,
        location,
        selectedMinAge,
        selectedMaxAge,
        selectedMinPrice,
        selectedMaxPrice
      );
    }, doneTypingInterval);
  });

  // On keydown, clear the countdown
  jQuery("#seller_search").on("keydown", function () {
    clearTimeout(typingTimer);
  });

  //runs on location dropdown changes
  // jQuery('#location').change(function() {
  jQuery(document).on("click", "#apply-button", function () {
    location = jQuery("#location").val();
    searchValue = jQuery("#seller_search").val();

    // after select the filter button chages starts
    let h5Element = jQuery("#seller-filter-dropdown").find("h5");
    jQuery(".so-filters-dropdowns").toggleClass("close-state");
    jQuery(".seller-filter-dropdown-form").hide();
    jQuery(document.body).toggleClass("seller-filter-opened");
    if (jQuery(".so-filters-dropdowns").hasClass("close-state")) {
      jQuery(h5Element).text("Close");
    } else {
      jQuery(h5Element).text("Filter");
    }
    selectedMinAge = jQuery("#age-start").val();
    selectedMaxAge = jQuery("#age-end").val();

    selectedMinPrice = jQuery("#price-start").val();
    selectedMaxPrice = jQuery("#price-end").val();
    // after select the filter button chages ends

    seller_filter(
      button_id,
      button_target,
      tab,
      selectedIds,
      searchValue,
      location,
      selectedMinAge,
      selectedMaxAge,
      selectedMinPrice,
      selectedMaxPrice
    );
  });

  //age slider ----------------------------------------------- age slide----------------------
  jQuery(function () {
    // Retrieve min and max ages from the data attributes
    var minAge = parseInt(jQuery("#slider-range-age").attr("data-min-age"), 10);
    var maxAge = parseInt(jQuery("#slider-range-age").attr("data-max-age"), 10);

    // Initialize the jQuery UI slider
    jQuery("#slider-range-age").slider({
      range: true,
      min: minAge,
      max: maxAge,
      values: [minAge, maxAge],
      slide: function (event, ui) {
        // Update the select elements with the slider values
        jQuery("#age-start").val(ui.values[0]);
        jQuery("#age-end").val(ui.values[1]);

        // Update the data attributes
        jQuery("#slider-range-age").attr("data-min-age", ui.values[0]);
        jQuery("#slider-range-age").attr("data-max-age", ui.values[1]);

        // Trigger the change event manually on the selects
        jQuery("#age-start").trigger("change");
        jQuery("#age-end").trigger("change");
      },
    });

    // Set the initial values of the selects based on the slider
    // jQuery("#age-start").val(jQuery("#slider-range-age").slider("values", 0));
    // jQuery("#age-end").val(jQuery("#slider-range-age").slider("values", 1));

    // Handle changes to the select dropdowns (in case user changes them directly)
    jQuery("#age-start, #age-end").change(function () {
      selectedMinAge = parseInt(jQuery("#age-start").val(), 10) || minAge;
      selectedMaxAge = parseInt(jQuery("#age-end").val(), 10) || maxAge;

      // Ensure selectedMinAge is less than or equal to selectedMaxAge
      if (selectedMinAge > selectedMaxAge) {
        [selectedMinAge, selectedMaxAge] = [selectedMaxAge, selectedMinAge];
        jQuery("#age-start").val(selectedMinAge);
        jQuery("#age-end").val(selectedMaxAge);
      }

      // Update the slider values
      jQuery("#slider-range-age").slider("values", [
        selectedMinAge,
        selectedMaxAge,
      ]);

      // Update the data attributes
      jQuery("#slider-range-age").attr("data-min-age", selectedMinAge);
      jQuery("#slider-range-age").attr("data-max-age", selectedMaxAge);
    });
  });

  //price saliva -----------------------------------------------price saliva ---------------------
  jQuery(function () {
    // Set the minimum and maximum price for the slider
    var minPrice = 0;
    var maxPrice = 1000;

    // Initialize the slider with the predefined range
    jQuery("#slider-range").slider({
      range: true,
      min: minPrice,
      max: maxPrice,
      values: [minPrice, maxPrice],
      slide: function (event, ui) {
        // Update the input fields with the slider values
        jQuery("#price-start").val(ui.values[0]);
        jQuery("#price-end").val(ui.values[1]);

        // Trigger the change event manually on the inputs
        jQuery("#price-start").trigger("change");
        jQuery("#price-end").trigger("change");
      },
    });

    // Set the initial values of the inputs based on the slider
    // jQuery("#price-start").val(jQuery("#slider-range").slider("values", 0));
    // jQuery("#price-end").val(jQuery("#slider-range").slider("values", 1));

    // Handle changes to the input fields (in case the user changes them directly)
    jQuery("#price-start, #price-end").change(function () {
      selectedMinPrice = parseFloat(jQuery("#price-start").val()) || minPrice;
      selectedMaxPrice = parseFloat(jQuery("#price-end").val()) || maxPrice;

      // Ensure selectedMinPrice is less than or equal to selectedMaxPrice
      if (selectedMinPrice > selectedMaxPrice) {
        [selectedMinPrice, selectedMaxPrice] = [
          selectedMaxPrice,
          selectedMinPrice,
        ];
        jQuery("#price-start").val(selectedMinPrice);
        jQuery("#price-end").val(selectedMaxPrice);
      }

      // Update the slider values
      jQuery("#slider-range").slider("values", [
        selectedMinPrice,
        selectedMaxPrice,
      ]);
    });
  });

  // Function to get URL parameters using jQuery
  function getUrlParameter(name) {
    // Construct a regex pattern to find the parameter in the URL
    let results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
      window.location.href
    );
    return results ? decodeURIComponent(results[1]) || null : null;
  }

  // Check if 'seller_search' exists in the URL
  var sellerSearch = getUrlParameter("seller_search");
  var catSearch = getUrlParameter("cat");
  if (sellerSearch) {
    jQuery("#seller_search").val(sellerSearch);
  } else {
    sellerSearch = undefined;
  }

  if (catSearch) {
    // Check the checkbox that matches the catSearch value in the data-cat-slug attribute
    jQuery(`input.category-class[data-cat-slug="${catSearch}"]`).prop(
      "checked",
      true
    );
    let cat_id = jQuery(
      `input.category-class[data-cat-slug="${catSearch}"]`
    ).data("id");
    selectedIds.push(cat_id);
  } else {
    selectedIds = [];
  }
  seller_filter(
    undefined,
    undefined,
    undefined,
    selectedIds,
    sellerSearch,
    undefined,
    undefined,
    undefined,
    undefined,
    undefined
  );
  // seller_filter();
});

function seller_filter(
  button_id = "",
  button_target = "",
  tab = "",
  cat_id = [],
  searchValue = "",
  location = "",
  selectedMinAge = "",
  selectedMaxAge = "",
  selectedMinPrice = "",
  selectedMaxPrice = ""
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
      selectedMinAge: selectedMinAge,
      selectedMaxAge: selectedMaxAge,
      selectedMinPrice: selectedMinPrice,
      selectedMaxPrice: selectedMaxPrice,
    },
    beforeSend: function () {
      jQuery("#new-seller-tab-loader").show();
      jQuery("#seller-pills-tabContent").empty();
    },
    success: function (response) {
      if (response.success) {
        var htmlContent = response.data.html;
        var numUsers = response.data.num_users;
        jQuery("#seller-pills-tabContent").html(htmlContent);
        jQuery("#seller-count").text(numUsers + ' Sellers');
      }
    },
    complete: function () {
      jQuery("#new-seller-tab-loader").hide();
    },
  });
}

// User is "finished typing," do something
function doneTyping(
  button_id,
  button_target,
  tab,
  selectedIds,
  searchValue,
  location,
  selectedMinAge,
  selectedMaxAge,
  selectedMinPrice,
  selectedMaxPrice
) {
  searchValue = jQuery("#seller_search").val();
  location = jQuery("#location").val();
  selectedMinAge = parseInt(jQuery("#age-start").val(), 10);
  selectedMaxAge = parseInt(jQuery("#age-end").val(), 10);

  selectedMinPrice = jQuery("#price-start").val();
  selectedMaxPrice = jQuery("#price-end").val();
  seller_filter(
    button_id,
    button_target,
    tab,
    selectedIds,
    searchValue,
    location,
    selectedMinAge,
    selectedMaxAge,
    selectedMinPrice,
    selectedMaxPrice
  );
}
