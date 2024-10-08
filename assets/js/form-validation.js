$(function() {
  'use strict';
  
  $(function() {    
    $("#signupForm").validate({
      rules: {
        name: {
          required: true,
          minlength: 3
        },
        fname: {
          required: true,
          minlength: 3
        },
        lname: {
          required: true,
          minlength: 3
        },
        password: {
          required: true,
          minlength: 5
        },
        confirm_password: {
          required: true,
          minlength: 5,
          equalTo: "#password"
        },
        email: {
          required: true,
          email: true
        },
        topic: {
          required: "#newsletter:checked",
          minlength: 2
        },
        agree: "required"
      },
      messages: {
        name: {
          required: "Please enter a name",
          minlength: "Name must consist of at least 3 characters"
        },
        fname: {
          required: "Please enter a first name",
          minlength: "Name must consist of at least 3 characters"
        },
        lname: {
          required: "Please enter a last name",
          minlength: "Name must consist of at least 3 characters"
        },
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 5 characters long"
        },
        confirm_password: {
          required: "Please provide a password",
          minlength: "Your password must be at least 5 characters long",
          equalTo: "Please enter the same password as above"
        },
        email: "Please enter a valid email address",
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
  });
  $(function() {    
    $("#loginForm").validate({
      rules: {
        password: {
          required: true,
          minlength: 4
        },        
        email: {
          required: true,
          email: true
        },        
        agree: "required"
      },
      messages: {        
        password: {
          required: "Please provide a password",
          minlength: "Your password must be at least4 characters long"
        },       
        email: "Please enter a valid email address",
      },
      errorPlacement: function(label, element) {
        label.addClass('mt-2 text-danger');
        label.insertAfter(element);
      },
      highlight: function(element, errorClass) {
        $(element).parent().addClass('has-danger')
        $(element).addClass('form-control-danger')
      }
    });
  });
  
});