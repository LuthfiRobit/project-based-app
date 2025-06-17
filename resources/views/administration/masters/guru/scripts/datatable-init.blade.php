  <script>
      const table = $('#example').DataTable({
          processing: true,
          serverSide: true,
          ajax: {
              url: '{{ route('master.guru.list') }}', // Adjust to your route
              type: 'GET',
              data: function(d) {
                  d.filter_status = $('#filter_status').val(); // Send filter status
                  d.filter_status_guru = $('#filter_status_guru').val(); // Send filter status
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
                  data: 'nip',
                  name: 'nip',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'nama_guru',
                  name: 'nama_guru',
                  orderable: false,
                  searchable: true
              },

              {
                  data: 'jabatan',
                  name: 'jabatan',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'no_telepon',
                  name: 'no_telepon',
                  orderable: false,
                  searchable: true
              },
              {
                  data: 'status_guru',
                  name: 'status_guru',
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

      const performOptimizedSearch = _.debounce(query => {
          if (query.length >= 4 || query.length === 0) table.search(query).draw();
      }, 3000);

      $('#example_filter input').unbind().on('input', function() {
          performOptimizedSearch($(this).val());
      });

      $('#filter_status, #filter_status_guru').change(() => table.ajax.reload());

      $('#selectAll').click(function() {
          $('.table-checkbox:not(:disabled)').prop('checked', this.checked);
      });
  </script>
