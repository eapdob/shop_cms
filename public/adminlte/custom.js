$(document).ready(function() {
   $(document).on('click', '.delete', function() {
      let res = confirm ('Confirm action');
      if (!res) return false;
   });
});