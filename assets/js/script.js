/**
 * Logs a warning message to the console.
 *
 * @memberof window
 */
jQuery(document).ready(function () {
  console.log(
    "%cWarning: This is a browser feature intended for developers. Do not copy-paste anything from untrusted sources, as it may be a scam to gain unauthorized access to your accounts or personal information.",
    "color: red; font-size: 20px; font-weight: bold;"
  );
});

// home page header search button===============================================================

(function ($) {
  $(function () {
    $(".toggle-overlay").click(function () {
      $("aside").toggleClass("open");
    });
    $("#msform .login-forgot-password").insertAfter(
      "#loginform .login-remember label"
    );
  });
})(jQuery);

const navToggleButton = document.querySelector(".navbar-toggler");
const iconElement = navToggleButton.querySelector(".bi-list");

function updateIcon() {
  const isExpanded = navToggleButton.getAttribute("aria-expanded") === "true";

  if (isExpanded) {
    // If the collapsible menu is expanded, change the class of the "bi-list" icon to "bi-x-circle-fill"
    iconElement.classList.remove("bi-list");
    iconElement.classList.add("bi-x-circle-fill");
  } else {
    // If the collapsible menu is collapsed, change the class of the "bi-x-circle-fill" icon back to "bi-list"
    iconElement.classList.remove("bi-x-circle-fill");
    iconElement.classList.add("bi-list");
  }
}

// Call the updateIcon function on page load to set the initial icon state
updateIcon();

// Add a click event listener to the nav toggle button to handle icon updates on click
navToggleButton.addEventListener("click", function () {
  // Delay the icon update slightly to ensure the "aria-expanded" attribute is updated correctly
  setTimeout(updateIcon, 10);
});

// for logged in nav menu mobile
window.addEventListener("resize", function () {
  if (document.querySelector(".logged-in-menu .navbar-collapse") != null) {
    var button = document.querySelector(".logged-in-menu .navbar-collapse");
    if (window.innerWidth < 991) {
      button.classList.add("show");
    } else {
      button.classList.remove("show");
    }
  }
});

// Trigger the event on page load to set the initial state based on the window width
window.dispatchEvent(new Event("resize"));

// seller page filter dropdown js=========================================

document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("seller-filter-dropdown") != null) {
    // Get references to the "a" tag and the form
    const dropdownLink = document.getElementById("seller-filter-dropdown");
    const form = document.querySelector(".seller-filter-dropdown-form.filter");

    // Attach a click event listener to the "a" tag
    dropdownLink.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default link behavior (e.g., page reload)

      // Toggle the display style of the form based on its current state
      if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block"; // Show the form
      } else {
        form.style.display = "none"; // Hide the form
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("seller-filter-dropdown") != null) {
    // Get references to the link, h5, and icon
    const dropdownLink = document.getElementById("seller-filter-dropdown");
    const h5Element = dropdownLink.querySelector("h5");
    const iconElement = dropdownLink.querySelector(".bi-grid-fill");

    // Attach a click event listener to the link
    dropdownLink.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default link behavior (e.g., page reload)

      // Toggle the "close-state" class on the link's parent div
      dropdownLink.parentElement.classList.toggle("close-state");
      document.body.classList.toggle("seller-filter-opened");
      // Update the text of the h5 element based on the current state
      if (dropdownLink.parentElement.classList.contains("close-state")) {
        h5Element.textContent = "Close";
      } else {
        h5Element.textContent = "Filter";
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("seller-filter-dropdown location") != null) {
    // Get references to the "a" tag and the form
    const dropdownLink = document.getElementById(
      "seller-filter-dropdown location"
    );
    const form = document.querySelector(
      ".seller-filter-dropdown-form.location"
    );

    // Attach a click event listener to the "a" tag
    dropdownLink.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default link behavior (e.g., page reload)

      // Toggle the display style of the form based on its current state
      if (form.style.display === "none" || form.style.display === "") {
        form.style.display = "block"; // Show the form
      } else {
        form.style.display = "none"; // Hide the form
      }
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {
  if (document.getElementById("seller-filter-dropdown location") != null) {
    // Get references to the link, h5, and icon
    const dropdownLink = document.getElementById(
      "seller-filter-dropdown location"
    );
    const h5Element = dropdownLink.querySelector("h5");
    const iconElement = dropdownLink.querySelector("bi bi-geo-alt-fill");

    // Attach a click event listener to the link
    dropdownLink.addEventListener("click", function (event) {
      event.preventDefault(); // Prevent the default link behavior (e.g., page reload)

      // Toggle the "close-state" class on the link's parent div
      dropdownLink.parentElement.classList.toggle("close-state");

      // Update the text of the h5 element based on the current state
      if (dropdownLink.parentElement.classList.contains("close-state")) {
        h5Element.textContent = "Close";
      } else {
        h5Element.textContent = "Location";
      }
    });
  }
});

jQuery(document).ready(function () {
  if (jQuery("#seller-products th").length != 0) {
    //sort product table
    jQuery("#sort-products").change(function () {
      jQuery("#so-my-spitout_productsOption").html(
        jQuery("#sort-products option:selected").text()
      );
      jQuery(this).width(jQuery("#so-my-spitout-sort-products").width());
    });

    jQuery(document.body).on("change", "#sort-products", function (e) {
      // console.log('pppppp');
      jQuery("#seller-products th").eq(3).removeClass("desc asc");
      jQuery("#seller-products th").eq(2).removeClass("desc asc");

      if (jQuery(this).val() == "price_lh") {
        jQuery("#seller-products th").eq(3).addClass("asc");
        jQuery("#seller-products th")[3].click();
      } else if (jQuery(this).val() == "price_hl") {
        jQuery("#seller-products th").eq(3).addClass("desc");
        jQuery("#seller-products th")[3].click();
      } else if (jQuery(this).val() == "sales_lh") {
        jQuery("#seller-products th").eq(2).addClass("asc");
        jQuery("#seller-products th")[2].click();
      } else if (jQuery(this).val() == "sales_hl") {
        jQuery("#seller-products th").eq(2).addClass("desc");
        jQuery("#seller-products th")[2].click();
      }
    });

    jQuery(function () {
      jQuery(document).on("click", "#seller-products th", function () {
        var index = jQuery(this).index();
        var rows = [];

        jQuery("#seller-products tbody tr").each(function (index, row) {
          rows.push(jQuery(row).detach());
        });

        rows.sort(function (a, b) {
          var aValue = jQuery(a)
              .find("td")
              .eq(index)
              .text()
              .trim()
              .replace("$", ""),
            bValue = jQuery(b)
              .find("td")
              .eq(index)
              .text()
              .trim()
              .replace("$", "");

          if (parseFloat(aValue)) {
            return parseFloat(aValue) > parseFloat(bValue)
              ? 1
              : parseFloat(aValue) < parseFloat(bValue)
              ? -1
              : 0;
          } else {
            return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
          }
        });

        if (jQuery(this).hasClass("desc")) {
          rows.reverse();
        }

        jQuery.each(rows, function (index, row) {
          jQuery("#seller-products tbody").append(row);
        });
      });
    });

    //sort sales table
    jQuery("#sort-sales").change(function () {
      // console.log(jQuery("#sort-sales option:selected").text());
      jQuery("#so-my-spitout_salesOption").html(
        jQuery("#sort-sales option:selected").text()
      );
      jQuery(this).width(jQuery("#so-my-spitout-sort-sales").width());
    });

    jQuery(document.body).on("change", "#sort-sales", function (e) {
      jQuery("#seller-sales th").eq(3).removeClass("desc asc");
      jQuery("#seller-sales th").eq(4).removeClass("desc asc");

      if (jQuery(this).val() == "amount_lh") {
        jQuery("#seller-sales th").eq(3).addClass("asc");
        jQuery("#seller-sales th")[3].click();
      } else if (jQuery(this).val() == "amount_hl") {
        jQuery("#seller-sales th").eq(3).addClass("desc");
        jQuery("#seller-sales th")[3].click();
      } else if (jQuery(this).val() == "status_complete") {
        jQuery("#seller-sales th").eq(4).addClass("asc");
        jQuery("#seller-sales th")[4].click();
      } else if (jQuery(this).val() == "status_not_complete") {
        jQuery("#seller-sales th").eq(4).addClass("desc");
        jQuery("#seller-sales th")[4].click();
      }
    });

    jQuery(function () {
      jQuery(document).on("click", "#seller-sales th", function () {
        var index = jQuery(this).index();
        var rows = [];

        jQuery("#seller-sales tbody tr").each(function (index, row) {
          rows.push(jQuery(row).detach());
        });

        rows.sort(function (a, b) {
          var aValue = jQuery(a)
              .find("td")
              .eq(index)
              .text()
              .replace("$", "")
              .replace(" ", ""),
            bValue = jQuery(b)
              .find("td")
              .eq(index)
              .text()
              .replace("$", "")
              .replace(" ", "");

          if (parseFloat(aValue)) {
            return parseFloat(aValue) > parseFloat(bValue)
              ? 1
              : parseFloat(aValue) < parseFloat(bValue)
              ? -1
              : 0;
          } else {
            return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
          }
        });

        if (jQuery(this).hasClass("desc")) {
          rows.reverse();
        }

        jQuery.each(rows, function (index, row) {
          jQuery("#seller-sales tbody").append(row);
        });
      });
    });

    // Attach an event listener to the date picker
    jQuery("#datepicker").on("change", function () {
      // Retrieve the selected date value
      var selectedDate = jQuery(this).val();

      // Get the table body and all rows except the first (header) row
      var tableBody = jQuery("#seller-sales tbody");
      var rows = tableBody.find("tr");

      // Move the row with the matching date to the top
      rows.each(function () {
        var dateCell = jQuery(this).find("td:nth-child(3)"); // Assuming the date is in the third column

        if (dateCell.text() === selectedDate) {
          // Prepend the matching row to the table body
          jQuery(this).prependTo(tableBody);
        }
      });

      // Sort the remaining rows based on their original positions
      rows
        .sort(function (a, b) {
          return jQuery(a).index() - jQuery(b).index();
        })
        .appendTo(tableBody);
    });

    //test for iterating over child elements
    var langArray = [];
    jQuery(".so-producticonpicker option").each(function () {
      var img = jQuery(this).attr("data-thumbnail");
      var text = this.innerText;
      var value = jQuery(this).val();
      var item =
        '<li><img src="' +
        img +
        '" alt="" value="' +
        value +
        '"/><span>' +
        text +
        "</span></li>";
      langArray.push(item);
    });

    jQuery("#so-addp-a").html(langArray);

    //Set the button value to the first el of the array
    jQuery(".btn-select").html(langArray[0]);
    jQuery(".btn-select").attr("value", "selecticon");

    //change button stuff on click
    jQuery("#so-addp-a li").click(function () {
      // console.log("gg");
      jQuery("#add_new_product_form")
        .find('[name="product_icon"]')
        .val(jQuery(this).find("img").attr("value"));
      var img = jQuery(this).find("img").attr("src");
      var value = jQuery(this).find("img").attr("value");
      var text = this.innerText;
      var item =
        '<li><img src="' + img + '" alt="" /><span>' + text + "</span></li>";
      jQuery(".btn-select").html(item);
      jQuery(".btn-select").attr("value", value);
      jQuery(".so-addp-b").toggle();
      //console.log(value);
    });

    jQuery(".btn-select").click(function () {
      jQuery(".so-addp-b").toggle();
    });

    //Edit product option
    var langArraye = [];
    jQuery(".so-producticonpicker-edit option").each(function () {
      // console.log("hh");
      // jQuery('#edited-product-icon').val(jQuery(this).val());
      var imge = jQuery(this).attr("data-thumbnail");
      var texte = this.innerText;
      var valuee = jQuery(this).val();
      // alert(texte);
      // alert(valuee);
      var iteme =
        '<li><img src="' +
        imge +
        '" alt="" value="' +
        valuee +
        '"/><span>' +
        texte +
        "</span></li>";
      langArraye.push(iteme);
    });

    jQuery("#so-editp-a").html(langArraye);

    //Set the button value to the first el of the array
    jQuery(".btn-selecte").html(langArraye[0]);
    jQuery(".btn-selecte").attr("value", "selecticone");

    //change button stuff on click
    jQuery("#so-editp-a li").click(function () {
      var imge = jQuery(this).find("img").attr("src");
      var valuee = jQuery(this).find("img").attr("value");
      var texte = this.innerText;
      var iteme =
        '<li><img src="' + imge + '" alt="" /><span>' + texte + "</span></li>";
      jQuery(".btn-selecte").html(iteme);
      jQuery(".btn-selecte").attr("value", valuee);
      jQuery(".so-editp-b").toggle();
      //console.log(value);
    });

    jQuery(".btn-selecte").click(function () {
      jQuery(".so-editp-b").toggle();
    });
  }

  /* Seller page order validation js */

  jQuery("#spitout_order").prop("disabled", true);
  jQuery("input:checkbox").click(function () {
    if (jQuery(this).is(":checked")) {
      jQuery("#spitout_order").prop("disabled", false);
    } else {
      if (jQuery("#selected_product").filter(":checked").length < 1) {
        jQuery("#spitout_order").attr("disabled", true);
      }
    }
  });

  /* Seller page post-feed  validation js */

  jQuery("#so-feed-submit-form").prop("disabled", true);
  jQuery("#post-content , #so_create_post_media").on(
    "input change",
    function () {
      if (
        jQuery("#post-content").val() !== "" ||
        jQuery("#so_create_post_media").val() !== ""
      ) {
        jQuery("#so-feed-submit-form").prop("disabled", false);
      } else {
        jQuery("#so-feed-submit-form").prop("disabled", true);
      }
    }
  );

  /* displays the text counter in edit bio field */
  jQuery('textarea[name="so-bio"]').on("input", function () {
    var count = jQuery(this).val().length;
    var colorClass = "";
    if (count >= 180) {
      count = 180; // Limit the count to 180
      colorClass = "text-danger"; // Add the class when the count is 180
    }
    jQuery(this)
      .next("small")
      .html(
        '<div class="so-text-count ' + colorClass + '">' + count + "/180</div>"
      );
  });

  // DATE PICKER
  jQuery("#datepicker").datepicker({
    showOn: "button",
    buttonImage: "",
    buttonImageOnly: true,
    // buttonText: "Select date",
  });

  jQuery(".ui-datepicker-trigger").click(function () {
    //show the selected date input after any date is selected
    [
      ...document.querySelectorAll(
        "table.ui-datepicker-calendar tbody tr td a"
      ),
    ].forEach(function (day) {
      day.addEventListener("click", function () {
        document.querySelector(
          ".so-my-spitout-calendar-datepicker input"
        ).style.display = "block";
      });
    });
  });
});

// custom sort in my-spitout
jQuery(".so-my-spitout-select-dropdown__button").on("click", function () {
  jQuery(".so-my-spitout-select__list").toggleClass("active");
});
jQuery(".so-my-spitout-select-dropdown__list-item").on("click", function () {
  var itemValue = jQuery(this).data("value");
  // console.log(itemValue);
  jQuery(".so-my-spitout-select-dropdown__button span")
    .text(jQuery(this).text())
    .parent()
    .attr("data-value", itemValue);
  jQuery(".so-my-spitout-select-dropdown__list").toggleClass("active");
});

// multilevel register

// register page js==================================================

jQuery(document).ready(function () {
  jQuery("#so-create-acc .so-action-button").on("click", function () {
    // Remove 'active' class from the current active step
    jQuery("#so-register-steps-bar li:first-child").removeClass("active");
    // Add 'active' class to the 'verify' step
    jQuery("#so-register-steps-bar li:nth-child(2)").addClass("active");
  });
  jQuery("#so-verify-acc .so-action-button").on("click", function () {
    // Remove 'active' class from the current active step
    jQuery("#so-register-steps-bar li:nth-child(2)").removeClass("active");
    // Add 'active' class to the 'verify' step
    jQuery("#so-register-steps-bar li:nth-child(3)").addClass("active");
  });
  jQuery("#so-complete-verify .so-action-button").on("click", function () {
    // Remove 'active' class from the current active step
    jQuery("#so-register-steps-bar li:nth-child(3)").removeClass("active");
    // Add 'active' class to the 'verify' step
  });
  jQuery("#so-verify-acc .previous").on("click", function () {
    // Remove 'active' class from the current active step
    jQuery("#so-register-steps-bar li:nth-child(1)").addClass("active");
    jQuery("#so-register-steps-bar li:nth-child(2)").removeClass("active");
    // Add 'active' class to the 'verify' step
  });
  jQuery("#so-complete-verify .previous").on("click", function () {
    // Remove 'active' class from the current active step
    jQuery("#so-register-steps-bar li:nth-child(2)").addClass("active");
    jQuery("#so-register-steps-bar li:nth-child(3)").removeClass("active");
    // Add 'active' class to the 'verify' step
  });
});

// login form password js-----------------------------
jQuery("#show-password").on("click", function () {
  var input = jQuery("input#so-password"); // Use 'var' to declare the variable
  if (input.attr("type") === "password") {
    input.attr("type", "text");
    jQuery(this).removeClass("bi-eye-slash-fill"); // Toggle the classes for icon change
    jQuery(this).addClass("bi-eye-fill"); // Toggle the classes for icon change
  } else {
    input.attr("type", "password");
    jQuery(this).addClass("bi-eye-slash-fill"); // Toggle the classes for icon change
    jQuery(this).removeClass("bi-eye-fill"); // Toggle the classes for icon change
  }
});

// login form password show
jQuery(document).ready(function () {
  // Function to update the top style of the <i> element
  function updateTopStyle() {
    if (jQuery("p.text-danger").length > 0) {
      // If there is a <p> element with class text-danger
      if (jQuery(window).width() <= 767) {
        // For small screens, set top to 15rem
        jQuery("#show-password").css("top", "15rem");
      } else {
        // For large screens, set top to 16.5rem
        jQuery("#show-password").css("top", "16.5rem");
      }
    }
  }

  // Call the updateTopStyle function when the page loads and on window resize
  updateTopStyle();
  jQuery(window).resize(updateTopStyle);

  // Register page open privacy agree modal and check on agree
  jQuery(document).on("click", "button.agree-terms", function () {
    // console.log("uuuuuu");
    jQuery("#agree").prop("checked", true);
  });

  // message notification on/off
  jQuery("#muteButton").click(function () {
    var icon = jQuery(this).find("i");
    var currentIconClass = icon.attr("class");

    if (currentIconClass.includes("bi-bell-fill")) {
      // Change the icon to unmute
      icon.removeClass("bi-bell-fill").addClass("bi-bell-slash-fill");

      // Change the tooltip text
      jQuery(this).attr("data-tooltip", "Unmute");
    } else {
      // Change the icon back to mute
      icon.removeClass("bi-bell-slash-fill").addClass("bi-bell-fill");

      // Change the tooltip text
      jQuery(this).attr("data-tooltip", "Mute");
    }
  });

  // Check if the screen width is greater than 767 pixels
  if (jQuery(window).width() > 767) {
    // Check if there are elements with class "text-danger" inside elements with class "login-title"
    if (jQuery(".login-title").find(".text-danger").length > 0) {
      // Adjust the "top" property of the element with ID "show-password"
      jQuery("#show-password").css("top", "16.5rem");
    }
  }
});

// seller page dropdown select toggle
jQuery(document).on("change", "#seller-option", function () {
  var backgroundSrc = jQuery("#seller-dropdown-after-img").attr("src");

  // Set background image
  jQuery(".so-seller-filter-dropdown1 select").css(
    "background-image",
    "url(" + backgroundSrc + ")"
  );

  // Set background size to 20px and background position to right
  jQuery(".so-seller-filter-dropdown1 select").css({
    "background-size": "20px",
    "background-position": "95% 50%",
  });

  // Show the image with id "seller-dropdown-after-img"
  jQuery("#seller-dropdown-after-img").show();
});

function getNextFourDays() {
  var dateArray = [];
  for (var i = 1; i <= 4; i++) {
    var date = new Date();
    date.setDate(date.getDate() + i);
    var formattedDate = date.getMonth() + 1 + "/" + date.getDate();
    dateArray.push(formattedDate);
  }
  return dateArray;
}

// chart for myspiout page
jQuery(document).ready(function () {
  const dateObj = new Date();
  let curr_month = dateObj.getMonth();
  var currCurrency =
    getCookieValue("wmc_current_currency") == "BTC" ? "BTC" : "USD";
  var currCurrencySymbol = currCurrency == "BTC" ? "฿" : "$";

  if (jQuery("#monthly_sales").length != 0) {
    //get monthly sales report
    // var currExchangeRate = jQuery('.curr-exchange-rate').val();

    var available_monthly_sales_data = JSON.parse(
      jQuery("#monthly_sales").val()
    );
    // console.log(available_monthly_sales_data);

    // if(currCurrency == 'USD'){
    //   Object.keys(available_monthly_sales_data).forEach(function(key) {
    //     // Multiply the value by the multiplier and update the value in the object
    //     myObject[key] = myObject[key] * multiplier;
    // });

    // console.log(available_monthly_sales_data);
    var monthly_sales_data = [];
    var all_months = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
      "August",
      "September",
      "October",
      "November",
      "December",
    ];

    // console.log(monthly_sales_data);

    all_months.forEach((month) => {
      if (month in available_monthly_sales_data) {
        monthly_sales_data.push(available_monthly_sales_data[month]);
        // console.log(available_monthly_sales_data[month]);
      } else {
        monthly_sales_data.push(0);
      }
    });

    // console.log(currCurrencySymbol);

    var monthlyChartCanvas = document
      .getElementById("sales-chart")
      .getContext("2d");
    var monthlySalesChart = new Chart(monthlyChartCanvas, {
      type: "line",
      data: {
        labels: all_months,
        datasets: [
          {
            label: "Earnings: " + currCurrencySymbol,
            data: monthly_sales_data,
            backgroundColor: "rgba(255, 216, 243)",
            borderColor: "rgba(255, 76, 203, 1)",
            fill: "origin",
            borderWidth: 5,
            tension: 0.2,
          },
        ],
      },
      options: {
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                if (currCurrency == "BTC") {
                  let label = "";
                  console.log(parseFloat(context.parsed.y.toFixed(6)));
                  if (context.parsed.y !== null) {
                    label += parseFloat(context.parsed.y.toFixed(6));
                  }
                  return "Earnings: " + currCurrencySymbol + label;
                }
              },
            },
          },
        },
        scales: {
          x: {},
          y: {
            beginAtZero: true,
            grid: {
              drawOnChartArea: false,
            },
          },
        },
      },
    });
  }

  if (jQuery("#weekly_sales").length != 0) {
    console.log(Chart.version);

    //get weekly sales report
    var available_weekly_sales_data = JSON.parse(jQuery("#weekly_sales").val());
    var weekly_sales_data = [];
    var all_weeks = [1, 2, 3, 4, 5];

    if (curr_month == 2) {
      all_weeks = [1, 2, 3, 4];
    }

    all_weeks.forEach((week) => {
      if (week in available_weekly_sales_data) {
        weekly_sales_data.push(available_weekly_sales_data[week]);
      } else {
        weekly_sales_data.push(0);
      }
    });

    const week_chart_data = {
      labels: all_weeks,
      datasets: [
        {
          label: "Earnings: " + currCurrencySymbol,
          data: weekly_sales_data,
          backgroundColor: [
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
          ],
          borderColor: "rgba(255, 216, 243)",
          pointStyle: false,
          fill: "origin",
          borderWidth: 0,
          tension: 0.2,
          borderRadius: 100,
        },
      ],
    };

    // Find the index of the highest value
    const weeklyMaxIndex = week_chart_data.datasets[0].data.indexOf(
      Math.max(...week_chart_data.datasets[0].data)
    );

    // Set a different color for the highest valued bar
    week_chart_data.datasets[0].backgroundColor[weeklyMaxIndex] =
      "rgba(255, 76, 203, 1)";

    var weeklyChartCanvas = document
      .getElementById("best-week-chart")
      .getContext("2d");
    var weeklySalesChart = new Chart(weeklyChartCanvas, {
      type: "bar",
      data: week_chart_data,
      options: {
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                if (currCurrency == "BTC") {
                  let label = "";
                  console.log(parseFloat(context.parsed.y.toFixed(6)));
                  if (context.parsed.y !== null) {
                    label += parseFloat(context.parsed.y.toFixed(6));
                  }
                  return "Earnings: " + currCurrencySymbol + label;
                }
              },
            },
          },
        },
        scales: {
          x: {
            grid: {
              drawOnChartArea: false,
            },
          },
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }

  if (jQuery("#daily_sales").length != 0) {
    //get daily sales report for the best 7 days of the current month
    var available_daily_sales_data = JSON.parse(jQuery("#daily_sales").val());

    if (Object.keys(available_daily_sales_data).length == 1) {
      let nextfourdays = getNextFourDays();

      nextfourdays.forEach((date) => {
        available_daily_sales_data[date] = 0;
      });
    }

    var daily_sales_data = Object.values(available_daily_sales_data);
    var best_days = Object.keys(available_daily_sales_data);

    const daily_chart_data = {
      labels: best_days,
      datasets: [
        {
          label: "Earnings: " + currCurrencySymbol,
          data: daily_sales_data,
          backgroundColor: [
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
            "rgba(255, 216, 243)",
          ],
          borderColor: "rgba(255, 216, 243)",
          pointStyle: false,
          fill: "origin",
          tension: 0.2,
          borderRadius: 100,
        },
      ],
    };

    // Find the index of the highest value
    const dailyMaxIndex = daily_chart_data.datasets[0].data.indexOf(
      Math.max(...daily_chart_data.datasets[0].data)
    );

    // Set a different color for the highest valued bar
    daily_chart_data.datasets[0].backgroundColor[dailyMaxIndex] =
      "rgba(255, 76, 203)";

    var dailyChartCanvas = document
      .getElementById("best-days-chart")
      .getContext("2d");
    var dailySalesChart = new Chart(dailyChartCanvas, {
      type: "bar",
      data: daily_chart_data,
      options: {
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: function (context) {
                if (currCurrency == "BTC") {
                  let label = "";
                  console.log(parseFloat(context.parsed.y.toFixed(6)));
                  if (context.parsed.y !== null) {
                    label += parseFloat(context.parsed.y.toFixed(6));
                  }
                  return "Earnings: " + currCurrencySymbol + label;
                }
              },
            },
          },
        },
        scales: {
          x: {
            grid: {
              drawOnChartArea: false,
            },
          },
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }
});

jQuery(document).ready(function () {
  //===========sales tab order details modal start
  jQuery(document).on("click", ".view-sale-details", function () {
    let sales_data = JSON.parse(jQuery(this).attr("data-order-details"));
    let modal = jQuery("#sale-details");

    modal.find(".order_id").html("Order Id: " + sales_data.id);
    modal
      .find(".tracking_id")
      .html("Tracking Number: " + sales_data.tracking_id);
    modal.find(".total").html("Total: " + sales_data.total);
    modal
      .find(".shipping")
      .html("Shipping address: " + sales_data.shipping_address);
    modal
      .find(".customer_name")
      .html("Customer name: " + sales_data.customer_name);
    modal.find(".purchase_date").html("Purchase Date: " + sales_data.date);
    modal.find(".status").html("Status: " + sales_data.status);

    let items = "";

    let lables = ["", "Qty:", "PPU:"];

    sales_data.items.forEach((item) => {
      items += "<tr>";
      for (let index = 0; index < item.length; index++) {
        items += "<td>" + lables[index] + item[index] + "</td>";
      }
      items += "</tr>";
    });

    modal.find("tbody").html(items);
  });
  //===========sales tab order details modal ends

  //==========seller fitler page starts
  // Initial reset of form fields
  resetFilterFormFields();

  
  // Function to get URL parameters using jQuery
  function getUrlParameter(name) {
   // Construct a regex pattern to find the parameter in the URL
   let results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
     window.location.href
   );
   return results ? decodeURIComponent(results[1]) || null : null;
 }

  // Event handler for reset button
  jQuery(document).on("click", "#reset-button", function () {
    resetFilterFormFields();
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
    jQuery(".category-class").prop("checked", false);
    var sellerSearch = getUrlParameter("seller_search");
    if (sellerSearch) {
      jQuery("#seller_search").val(sellerSearch);
    } else {
      sellerSearch = undefined;
    }
    seller_filter(
      undefined,
      undefined,
      undefined,
      [],
      sellerSearch,
      undefined,
      undefined,
      undefined,
      undefined,
      undefined
    );
  });
  //==========seller fitler page ends
});

/**
 * Functions that resets the form fields on the seller filter page.
 */
function resetFilterFormFields() {
  jQuery("#category").val("");
  jQuery("#top-sellers").val("");
  jQuery("#age-start").val("");
  jQuery("#age-end").val("");
  jQuery("#online").val("");
  jQuery("#price-start").val("");
  jQuery("#price-end").val("");
  jQuery("#location").val("");
}

function getCookieValue(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(";");
  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

jQuery(document).ready(function () {
  jQuery(document).on("click", "#spitout_order", function () {
    function createCookie(name, value, minutes) {
      var expires;
      if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + minutes * 60 * 1000);
        expires = "; expires=" + date.toGMTString();
      } else {
        expires = "";
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    }

    // console.log("🚀 ~ createCookie ~ name:", 'uoooooooo');

    createCookie("curr-exchange-rate", jQuery(".curr-exchange-rate").val(), 10);
  });
});
