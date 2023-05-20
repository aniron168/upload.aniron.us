document.addEventListener('DOMContentLoaded', function() {
    var forms = document.querySelectorAll('form');
  
    forms.forEach(function(form) {
      form.addEventListener('submit', function(event) {
        var inputs = form.querySelectorAll('input[type="text"], input[type="password"]');
  
        inputs.forEach(function(input) {
          if (!input.value) {
            alert('Please fill out all fields.');
            event.preventDefault();
            return;
          }
        });
      });
    });
  });
  
 
