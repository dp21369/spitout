(function ($) {
  $(document).ready(function () {
    /* TEST JS */
    $(".icon-search").on("click", function () {
      $(".icon-search").addClass("test");
    });

    $(".so-footer-lg .column").click(function () {
      $(this).toggleClass("show");
    });
  });
})(jQuery);

// registration js starts here

jQuery(document).ready(function ($) {
  $("#country").change(function () {
    var selectedCountry = $(this).val();

    // Make an AJAX request to fetch the states based on the selected country.
    $.ajax({
      url: "/wp-admin/admin-ajax.php", // The URL to your WordPress AJAX endpoint.
      type: "POST",
      data: {
        action: "get_states", // Create an AJAX action hook for this.
        country: selectedCountry,
      },
      success: function (response) {
        $("#state").html(response); // Update the state dropdown with the retrieved options.
      },
    });
  });
});

jQuery(document).ready(function () {
  const textarea = jQuery("#post-content, #sender-msg");

  textarea.on("input", function () {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
  });

  textarea.on("focus", function () {
    textarea.css("border-radius", "10px");
  });

  textarea.on("blur", function () {
    textarea.css("border-radius", "10px");
  });
});

jQuery(document).on("focus input", ".so-comment-content", function () {
  jQuery(this).css("height", "auto");
  jQuery(this).css("height", jQuery(this)[0].scrollHeight + "px");
});

// create post decrease height on posting

jQuery(document).ready(function () {
  jQuery("#so_create_new_post .so-create-posts").on("click", function () {
    // Delay the height decrease by 2 seconds
    setTimeout(function () {
      jQuery("#post-content").css("height", "55px");
    }, 4000);
  });
});
// create comment decrease height on posting

jQuery(document).ready(function () {
  jQuery(".spitout-create-comment-form-wrapper .so-create-comment").on(
    "click",
    function () {
      // Delay the height decrease by 2 seconds
      setTimeout(function () {
        jQuery(".so-comment-content").css("height", "45px");
      }, 2000);
    }
  );
});

// register modal scroll to bottom js
jQuery(document).ready(function () {
  jQuery("#scrollToBottom").on("click", function () {
    var modalBody = jQuery(".privacy-policy-modal .modal-dialog");
    var scrollHeight = modalBody.prop("scrollHeight");
    var animationSpeed = 5000; // Adjust the speed (in milliseconds) for the animation
    modalBody.animate({ scrollTop: scrollHeight }, animationSpeed, "swing");
  });
});

//sorting the 'top-buyers' table in the myspitout page
jQuery(document).ready(function () {
  var $table = jQuery("#overview-table");
  var $tbody = $table.find("tbody");
  var $rows = $tbody.find("tr");

  var $sortedRows = $rows.sort(function (a, b) {
    var totalA = +jQuery(a)
      .find("td:eq(2)")
      .text()
      .replace(/[^0-9\.]/g, "");
    var totalB = +jQuery(b)
      .find("td:eq(2)")
      .text()
      .replace(/[^0-9\.]/g, "");
    return totalB - totalA;
  });

  $tbody.append($sortedRows);

  // Delete all rows after the fifth row
  $tbody.find("tr:gt(4)").remove();
});

function generateRandomString(length, chars) {
  var result = "";
  for (var i = length; i > 0; --i)
    result += chars[Math.floor(Math.random() * chars.length)];
  return result;
}

//generate random tracking number
jQuery(document).ready(function () {
  jQuery("#generate-tracking-num").on("click tap", function () {
    var rString = generateRandomString(
      16,
      "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
    );
    jQuery("#tracking-num").val(rString);
  });
});

//open sales tab in myspitout page when user searches for any invoice id or uses the pagination
jQuery(document).ready(function () {
  // Get URL parameters
  var urlParams = new URLSearchParams(window.location.search);

  // Check if the formSubmitted parameter is present and is true
  if (
    urlParams.has("invoice-num") ||
    urlParams.get("invoice-search") ||
    urlParams.get("page")
  ) {
    jQuery("#so-my-spitout-sales-tab").click();
  }
});

//filter orders in the buyer's order page
jQuery(document).ready(function () {
  jQuery(".buyer-order-filters a").on("click tap", function (e) {
    e.preventDefault();
  });

  jQuery(".buyer-filter-all").on("click tap", function () {
    jQuery(".row.so-ordercard-row-content").show();
  });

  jQuery(".buyer-filter-arriving-soon").on("click tap", function () {
    jQuery(".row.so-ordercard-row-content").show();

    jQuery(".row.so-ordercard-row-content").each(function () {
      if (jQuery(this).find(".so-ordercard-status-arrivesoon").length == 0) {
        jQuery(this).hide();
      }
    });
  });

  jQuery(".buyer-filter-completed").on("click tap", function () {
    jQuery(".row.so-ordercard-row-content").show();

    jQuery(".row.so-ordercard-row-content").each(function () {
      if (jQuery(this).find(".so-ordercard-status-arrivesoon").length == 1) {
        jQuery(this).hide();
      }
    });
  });
});

// order page heading tab active class js
jQuery(document).ready(function () {
  jQuery(".buyer-order-filters a").click(function () {
    jQuery(this).addClass("active").siblings().removeClass("active");
  });
});

jQuery(document).ready(function () {
  if (jQuery(".spitout-currency-switcher").length == 0) {
    return;
  }

  function checkScreenWidth() {
    if (jQuery(window).width() <= 1280) {
      var elementToMove = document.querySelector(".spitout-currency-switcher");

      var liElement = document.createElement("li");

      elementToMove.parentNode.replaceChild(liElement, elementToMove);
      liElement.appendChild(elementToMove);

      var newLocation = document.querySelector(".so-navigation-menu");
      newLocation.appendChild(liElement);
    }
  }

  checkScreenWidth();

  // jQuery(window).resize(function () {
  //   console.log('++++++');
  //   checkScreenWidth();
  // });

  /*  currency converter js code for Terawallet Withdrawal */

  // Bitcoin to USD exchange rate
  var exchangeRate = jQuery("#wallet_withdrawal_amount").attr(
    "data-currencyrate"
  );

  var currentCurrency = jQuery("#wallet_withdrawal_amount").attr(
    "data-current-currency"
  );
  // Function to convert Bitcoin to USD
  function convertBitcoinToUSD(bitcoinValue) {
    if (currentCurrency == "USD") {
      var usdValue = bitcoinValue / exchangeRate;
    } else {
      var usdValue = bitcoinValue * exchangeRate;
    }

    return usdValue.toFixed(6);
  }

  // Event handler for keyup on Bitcoin input
  jQuery("#wallet_withdrawal_amount").on("keyup", function () {
    var bitcoinValue = parseFloat(jQuery(this).val());
    //console.log(bitcoinValue);
    if (!isNaN(bitcoinValue)) {
      var usdValue = convertBitcoinToUSD(bitcoinValue);
      if (currentCurrency == "USD") {
        jQuery("#spitout-currency-converter").text(
          "Converted Amount: ฿" + usdValue
        );
      } else {
        jQuery("#spitout-currency-converter").text(
          "Converted Amount: $" + usdValue
        );
      }
    } else {
      jQuery("#spitout-currency-converter").text("");
    }
  });

  /*  currency converter js code for Terawallet Wallet TopUp */

  var exchangeRateTopUp = jQuery("#woo_wallet_balance_to_add").attr(
    "data-currencyrate"
  );

  var currentCurrencyTopUp = jQuery("#woo_wallet_balance_to_add").attr(
    "data-current-currency"
  );
  // Function to convert Bitcoin to USD
  function convertBitcoinToUSDWalletTopUp(bitcoinValue) {
    if (currentCurrencyTopUp == "USD") {
      var usdValue = bitcoinValue / exchangeRateTopUp;
    } else {
      var usdValue = bitcoinValue * exchangeRateTopUp;
    }

    return usdValue.toFixed(6);
  }

  jQuery("#woo_wallet_balance_to_add").on("keyup", function () {
    var bitcoinValueTopup = parseFloat(jQuery(this).val());
    //console.log(bitcoinValueTopup);
    if (!isNaN(bitcoinValueTopup)) {
      var usdValue = convertBitcoinToUSDWalletTopUp(bitcoinValueTopup);
      //console.log(usdValue);
      if (currentCurrencyTopUp == "USD") {
        jQuery("#woo-wallet-conversion").text(
          bitcoinValueTopup + " USD($)" + "=" + usdValue + " BTC (Bitcoin)"
        );
      } else {
        jQuery("#woo-wallet-conversion").text(
          bitcoinValueTopup + " (BTC)" + "=" + usdValue + " USD ($)"
        );
      }
    } else {
      jQuery("#woo-wallet-conversion").text("");
    }
  });

  // if(currentCurrencyTopUp == "USD"){
  //   jQuery('#woo_wallet_balance_to_add').attr('min','10');
  // }
});

//save original price to local storage
jQuery(document).ready(function () {
  if (window.location.href.indexOf("/my-account/woo-wallet/add/") > -1) {
    jQuery(".woo-add-to-wallet").on("click", function () {
      var ogPrice = jQuery("#woo_wallet_balance_to_add").val();
      var ogCurrency = jQuery("#woo_wallet_balance_to_add").attr(
        "data-current-currency"
      );
      localStorage.setItem("ogWalletTopUpPrice", ogPrice);
      localStorage.setItem("ogWalletTopUpCurrency", ogCurrency);
    });
    // var currentCurrencyTopUp = jQuery("#woo_wallet_balance_to_add").attr("data-current-currency");
    // if(currentCurrencyTopUp == "USD"){
    //   jQuery('#woo_wallet_balance_to_add').attr('min','10');
    // }
  }
});

// set the price from local storage to checkout form page
jQuery(document).ready(function () {
  //check if in check out page
  if (window.location.href.indexOf("checkout") == -1) {
    return;
  }

  //check if product is wallet topup
  if (jQuery(".cart_item .product-name").html().indexOf("wallet") != -1) {
    return;
  }

  var ogWalletTopUpPrice = localStorage.getItem("ogWalletTopUpPrice");
  var ogWalletTopUpCurrency = localStorage.getItem("ogWalletTopUpCurrency");

  if (ogWalletTopUpPrice) {
    jQuery(".checkout-page-order-heading").append(
      " for amount: " + ogWalletTopUpCurrency + " " + ogWalletTopUpPrice
    );
  }

  //remove the price and currency after the wallet topup price is set
  jQuery(document).on("click", "#place_order", function () {
    localStorage.removeItem("ogWalletTopUpPrice");
    localStorage.removeItem("ogWalletTopUpCurrency");
  });
});

//autoamtically open the reviews tab in the individual seller page
jQuery(document).ready(function () {
  if (jQuery(".so-manage-products").length > 0) {
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("view") && urlParams.get("view") === "review") {
      jQuery("#pills-review-tab").click();
    }
  }
});

jQuery(document).ready(function () {
  jQuery(document).on("click", ".seller-more", function () {
    jQuery(".seller-search-categories").toggleClass("seller-expanded");
  });

  jQuery(document).on("click", ".banner-cat-select", function (e) {
    e.stopPropagation(); // Prevents the click from bubbling up to the document
    jQuery(".cat-option-dropdown").toggleClass("expanded");
  });

  // Detect clicks outside of .banner-cat-select to remove the 'expanded' class
  jQuery(document).on("click", function (e) {
    if (!jQuery(e.target).closest('.banner-cat-select').length) {
      jQuery(".cat-option-dropdown").removeClass("expanded");
    }
  });

});
