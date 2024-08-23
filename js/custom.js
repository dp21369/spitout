jQuery(document).ready(function () {

  function checkAgeOFConsent() {
    var age = {};

    function createDayAndYearOptions() {

      var dayList = jQuery('#verify-day');
      var yearList = jQuery('#verify-year');

      for (let day = 1; day <= 31; day++) {
        dayList.append('<option value="' + day + '">' + day + '</option>');
      }

      for (let year = 1940; year <= 2023; year++) {
        yearList.append('<option value="' + year + '">' + year + '</option>');
      }
    }

    function cookieExists(name) {
      var dc = document.cookie;
      var prefix = name + "=";
      var begin = dc.indexOf("; " + prefix);
      if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
      }
      else {
        begin += 2;
        var end = document.cookie.indexOf(";", begin);
        if (end == -1) {
          end = dc.length;
        }
      }
      return decodeURI(dc.substring(begin + prefix.length, end));
    }

    function getCookie(cname) {
      var name = cname + "=";
      var ca = document.cookie.split(';');
      for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    // starts the age verification process
    function initAge() {

      jQuery("#age-submit").on("click", function () {

        age['month'] = jQuery("#verify-month").val();
        age['day'] = jQuery("#verify-day").val();
        age['year'] = jQuery("#verify-year").val();

        if (age['month'] === "none" || age['day'] === "none" || age['year'] === "none") {
          return;
        }

        oldEnough();
      });
    }

    // Compares age entered with todays date 21 years ago...
    function oldEnough() {
      var birthDateString = age.year + "-" + age.month + "-" + age.day;
      var parts = birthDateString.split('-');
      var birthDate = new Date(parts[0], parts[1] - 1, parts[2]);
      birthDate.setFullYear(birthDate.getFullYear() + 21);

      if (birthDate <= new Date()) { //old enough
        //hide the popup
        jQuery('.overlay').css('display', 'none');
        jQuery('.overlay').css('opacity', '0');

        //set cookie
        setCookie('ageOfConsentReached', 'true', 99999);
        setCookie('ageOfUser', birthDateString, 99999);
      } else { //not old enough
        //show error message
        jQuery('#age-verification-err').css('display', 'block');

        //set cookie
        setCookie('ageOfConsentReached', 'false', 1);
      }
    }

    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function trapFocus(element) {
      if (typeof (document.getElementById('age-verfication-popup')) == 'undefined' || document.getElementById('age-verfication-popup') == null) {
        return;
      }
      element.querySelector("select").focus();
      var focusableEls = element.querySelectorAll('a[href]:not([disabled]), button:not([disabled]), textarea:not([disabled]), input[type="text"]:not([disabled]), input[type="radio"]:not([disabled]), input[type="checkbox"]:not([disabled]), select:not([disabled])');
      var firstFocusableEl = focusableEls[0];
      var lastFocusableEl = focusableEls[focusableEls.length - 1];
      var KEYCODE_TAB = 9;

      element.addEventListener('keydown', function (e) {
        var isTabPressed = (e.key === 'Tab' || e.keyCode === KEYCODE_TAB);

        if (!isTabPressed) {
          return;
        }

        if (e.shiftKey) /* shift + tab */ {
          if (document.activeElement === firstFocusableEl) {
            lastFocusableEl.focus();
            e.preventDefault();
          }
        } else /* tab */ {
          if (document.activeElement === lastFocusableEl) {
            firstFocusableEl.focus();
            e.preventDefault();
          }
        }
      });
    }


    //populate day and year options
    createDayAndYearOptions();

    //trap focus inside the modal
    trapFocus(document.getElementById('age-verfication-popup'));

    if (cookieExists('ageOfConsentReached') == null) { //cookie not set
      let home_url = jQuery('.curr-home-url').val();

      //check if user is in home url and check if user is logged in 
      if ((window.location.href != home_url) && jQuery('.so-login-btn').length == 1) {
        window.location = home_url;
      }

      // show the modal
      jQuery('.overlay').css('display', 'grid');
      jQuery('.overlay').css('opacity', '1');

      initAge();
    } else {
      if (getCookie('ageOfConsentReached') == 'false') {
        // show the modal
        jQuery('.overlay').css('display', 'grid');
        jQuery('.overlay').css('opacity', '1');

        //show error message
        jQuery('#age-verification-err').css('display', 'block');

        initAge();
      } else {

        // hide the modal
        jQuery('.overlay').css('display', 'none');
        jQuery('.overlay').css('opacity', '0');

      }
    }


    // converts '1940-2-1' to '1940-02-01'
    function formatDate(inputDate) {
      var parts = inputDate.split('-');

      for (var i = 0; i < parts.length; i++) {
        parts[i] = (parts[i].length === 1) ? '0' + parts[i] : parts[i];
      }

      var formattedDate = parts.join('-');

      return formattedDate;
    }

    /**
     * code that runs in the '/register/' page and 
     * auto populates the birthday using the cookie 
    */
    function autoPopulateRegisterPageDob() {
      if ((getCookie('ageOfConsentReached') == 'false') || !(jQuery('body').hasClass('page-template-template-register'))) {
        return;
      }

      let birthDateString = formatDate(getCookie('ageOfUser'));
      // console.log(getCookie('ageOfUser'));
      // console.log(birthDateString);
      let dobArr = birthDateString.split('-');
      let day = dobArr[2];
      let month = dobArr[1];
      let year = dobArr[0];

      // console.log(day);
      // console.log(month);
      // console.log(year);
      let monthList = jQuery('#month');
      let dayList = jQuery('#day');
      let yearList = jQuery('#year');

      monthList.val(month);
      dayList.val(day);
      yearList.val(year);
    };

    autoPopulateRegisterPageDob();
  }

  //check age of consent in homepage
  checkAgeOFConsent();

  //add select2js to cateogry selection in profile page
  if (jQuery('#so-category').length == 1) {
    jQuery('#so-category').select2();
  }

});