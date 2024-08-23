jQuery(document).ready(function () {
  var current_fs, next_fs, previous_fs; //fieldsets
  var opacity;


  // jQuery(".multistep-next").click(function () {
  //   console.log("zzzzzzzzzzzzzzzzzzzzzzzzz");

  //   // Reset error messages before revalidation
  //   resetErrorMessages();

  //   // Add your validation logic here
  //   var isValid = true;

  //   var fullName = jQuery("input[name='Fname']").val();
  //   var username = jQuery("input[name='uname']").val();
  //   var email = jQuery("input[name='email']").val();
  //   var password = jQuery("input[name='pwd']").val();
  //   // Validate email format
  //   var email_pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  //   // Calculate the user's age
  //   var selectedYear = parseInt(jQuery("#year").val());
  //   var selectedMonth = parseInt(jQuery("#month").val()) - 1;
  //   var selectedDate = parseInt(jQuery("#day").val());
  //   const date = new Date();


  //   const selectedDateObj = new Date(selectedYear, selectedMonth, selectedDate);
  //   date.setFullYear(date.getFullYear() - 21);

  //   if (selectedDateObj >= date) {
  //     console.log('You must be at least 21 years old to register.');
  //     appendErrorMessage("year", "You must be at least 21 years old to register.");
  //     isValid = false;
  //   }

  //   if (fullName === "") {
  //     appendErrorMessage("Fname", "Full Name is required.");
  //     isValid = false;
  //   }

  //   if (username === "") {
  //     appendErrorMessage("uname", "User Name is required.");
  //     isValid = false;
  //   }

  //   if (email === "") {
  //     appendErrorMessage("email", "Email is required.");
  //     isValid = false;
  //   } else if (!email_pattern.test(email)) {
  //     appendErrorMessage("email", "Please enter a valid email address.");
  //     isValid = false;
  //   }

  //   if (password === "") {
  //     appendErrorMessage("pwd", "Password is required.");
  //     isValid = false;
  //   }

  //   // If all validation passes, proceed to the next step
  //   if (isValid) {

  //     $.ajax({
  //       type: "POST",
  //       url: gamesajax.ajaxurl,
  //       data: {
  //         action: "loki_user_registration",
  //         name: name,
  //         email: email,
  //         organization_name: organization_name,
  //         organization_type: organization_type,
  //         country_name: country_name,
  //         role: role,
  //         zipcode: zipcode,
  //         nonce: nonce,
  //       },

  //     });
      // current_fs = jQuery(this).parent();
      // next_fs = jQuery(this).parent().next();

      // //Add Class Active
      // jQuery("#progressbar li")
      //   .eq(jQuery("fieldset").index(next_fs))
      //   .addClass("active");

      // //show the next fieldset
      // next_fs.show();
      // //hide the current fieldset with style
      // current_fs.animate(
      //   { opacity: 0 },
      //   {
      //     step: function (now) {
      //       // for making fielset appear animation
      //       opacity = 1 - now;

      //       current_fs.css({
      //         display: "none",
      //         position: "relative",
      //       });
      //       next_fs.css({ opacity: opacity });
      //     },
      //     duration: 600,
      //   }
      // );
  //   }
  // });

  // // Helper function to append error messages after the input elements
  // function appendErrorMessage(inputName, message) {
  //   var inputElement = jQuery("input[name='" + inputName + "']");
  //   inputElement.after("<div class='so-form-error'>" + message + "</div>");
  // }

  // // Helper function to reset error messages
  // function resetErrorMessages() {
  //   jQuery(".so-form-error").remove();
  // }




  // jQuery(".next").click(function () {
  //   current_fs = jQuery(this).parent();
  //   next_fs = jQuery(this).parent().next();

  //   //Add Class Active
  //   jQuery("#progressbar li")
  //     .eq(jQuery("fieldset").index(next_fs))
  //     .addClass("active");

  //   //show the next fieldset
  //   next_fs.show();
  //   //hide the current fieldset with style
  //   current_fs.animate(
  //     { opacity: 0 },
  //     {
  //       step: function (now) {
  //         // for making fielset appear animation
  //         opacity = 1 - now;

  //         current_fs.css({
  //           display: "none",
  //           position: "relative",
  //         });
  //         next_fs.css({ opacity: opacity });
  //       },
  //       duration: 600,
  //     }
  //   );
  // });

  jQuery(".previous").click(function () {
    current_fs = jQuery(this).parent();
    previous_fs = jQuery(this).parent().prev();

    //Remove class active
    jQuery("#progressbar li")
      .eq(jQuery("fieldset").index(current_fs))
      .removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate(
      { opacity: 0 },
      {
        step: function (now) {
          // for making fielset appear animation
          opacity = 1 - now;

          current_fs.css({
            display: "none",
            position: "relative",
          });
          previous_fs.css({ opacity: opacity });
        },
        duration: 600,
      }
    );
  });

  jQuery(".radio-group .radio").click(function () {
    jQuery(this).parent().find(".radio").removeClass("selected");
    jQuery(this).addClass("selected");
  });

  jQuery(".submit").click(function () {
    return false;
  });
});

// Month and day for birthdate=-=-=================================

document.addEventListener("DOMContentLoaded", function () {
  if (document.body.classList.contains("page-template-template-register")) {
    const monthDropdown = document.getElementById("month");
    const dayDropdown = document.getElementById("day");
    const yearDropdown = document.getElementById("year");

    // Function to update the days dropdown based on the selected month
    function updateDays() {
      const selectedMonth = parseInt(monthDropdown.value);
      const selectedYear = parseInt(yearDropdown.value);

      // Clear previous options
      dayDropdown.innerHTML = "";

      // Get the last day of the selected month
      const lastDay = new Date(selectedYear, selectedMonth, 0).getDate();

      // Generate day options dynamically with leading zeros
      for (let day = 1; day <= lastDay; day++) {
        const option = document.createElement("option");
        const formattedDay = day.toString().padStart(2, "0"); // Add leading zeros
        option.text = formattedDay;
        option.value = formattedDay;
        dayDropdown.appendChild(option);
      }
    }

    // Attach event listeners to month and year dropdowns
    monthDropdown.addEventListener("change", updateDays);
    yearDropdown.addEventListener("change", updateDays);

    // Example of how to populate the year dropdown with options from 1900 to 2100
    const currentYear = new Date().getFullYear();
    const today = new Date();
    const yyyy = today.getFullYear();
    for (let year = 1940; year <= yyyy; year++) {
      const option = document.createElement("option");
      option.text = year;
      option.value = year;
      yearDropdown.appendChild(option);
    }

    // Set the default year to the current year
    yearDropdown.value = currentYear;

    // Initialize the days dropdown based on the current month and year
    updateDays();
  }
});
/* End of age verification popup===================================================================== */

