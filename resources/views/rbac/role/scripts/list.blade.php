  <script>
      //   $(document).ready(function() {
      // Initialize DataTable
      var table = $('#example').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url: '{{ route('rbac.role.list') }}', // Adjust to your route
              type: 'GET',
              data: function(d) {

              }
          },
          columns: [{
                  data: 'aksi',
                  name: 'aksi',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'role_name',
                  name: 'role_name',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'role_description',
                  name: 'role_description',
                  orderable: false,
                  searchable: false

              }
          ],
          language: {
              searchPlaceholder: "Cari (min 4)...",
              search: ''
          },
      });


      const performOptimizedSearch = _.debounce(function(query) {
          try {
              if (query.length >= 4 || query.length === 0) {
                  table.search(query).draw();
              }
          } catch (error) {
              console.error("Error during search:", error);
          }
      }, 3000);

      $('#example_filter input').unbind().on('input', function() {
          performOptimizedSearch($(this).val());
      });
      //   });
  </script>
