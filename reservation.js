$(function() {
  // Validate the contact form
  $('#userinfo').validate({
    // Specify what the errors should look like
    // when they are dynamically added to the form
    errorElement: "label",
    wrapper: "td",
    errorPlacement: function(error, element) {
      error.insertBefore( element.parent().parent() );
      error.wrap("<tr class='error'></tr>");
      $("<td></td>").insertBefore(error);
    },
 
    // Add requirements to each of the fields
    rules: {
      name: {
        required: true,
        minlength: 2
      },
      email: {
        required: true,
        email: true
      },
      phone: {
        required: true,
        minlength: 10,
        maxlength: 10
      },
      time: {
        required: true
      },
      date: {
        required: true,
        minlength: 1
      },
      quantity: {
        required: true,
        minlength: 1
      },
      message: {
        required: false,
        minlength: 0
      }
    },
 
    // Specify what error messages to display
    // when the user does something horrid
    messages: {
      name: {
        required: "Please enter your name.",
        minlength: jQuery.format("At least {0} characters required.")
      },
      email: {
        required: "Please enter your email.",
        email: "Please enter a valid email."
      },
      phone: {
        required: "Please enter your phone number.",
        minlength: jQuery.format("Please enter a phone number."),
        maxlength: jQuery.format("Please enter a phone number.")
      },
      time: {
        required: "Please select an amount of time."
      },
      date: {
        required: "Please select a date."
      },
      quantity: {
        required: "Please enter the number of riders."
      },
      message: {
        required: "Please enter a message.",
        minlength: jQuery.format("At least {0} characters required.")
      }
    },
 
    // Use Ajax to send everything to processForm.php
    submitHandler: function(form) {
      $("#submit").attr("value", "Sending...");
      $(form).ajaxSubmit({
        target: "#response",
        success:
        
        function(responseText, statusText, xhr, $form) {
          $(form).slideUp("fast");
          $("#response").html(responseText).hide().slideDown("fast");
        }
      });
      return false;
    }
  });
});
