  <script>
      //   $(document).ready(function() {
      // Initialize DataTable
      var table = $('#example').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url: '{{ route('master.jabatan-guru.list') }}', // Adjust to your route
              type: 'GET',
              data: function(d) {
                  d.filter_status = $('#filter_status').val(); // Send filter status
              }
          },
          columns: [{
                  data: 'checkbox',
                  name: 'checkbox',
                  orderable: false,
                  searchable: false
              },
              {
                  data: 'aksi',
                  name: 'aksi',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'nama_jabatan',
                  name: 'nama_jabatan',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'status',
                  name: 'status',
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

      $('#filter_status').change(function() {
          table.ajax.reload(); // Reload DataTable with new filter
      });

      $('#selectAll').click(function() {
          var isChecked = $(this).prop('checked');
          $('.table-checkbox:not(:disabled)').prop('checked', isChecked);
      });
      //   });
  </script>
