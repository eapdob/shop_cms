$(document).ready(function() {
   $(document).on('click', '.delete', function() {
      let res = confirm ('Confirm action');
      if (!res) return false;
   });

   $('.sidebar a').each(function() {
      let location = window.location.protocol + '//' + window.location.host + window.location.pathname;
      let link = this.href;

      if (link === location) {
         let parent = $(this).parent();
         let hasTreeView = $(parent).closest('.has-treeview');

         if (!parent.hasClass('active')) {
            parent.addClass('active');
         }

         if (!hasTreeView.hasClass('menu-open')) {
            hasTreeView.addClass('menu-open');
         }
      }
   });
});