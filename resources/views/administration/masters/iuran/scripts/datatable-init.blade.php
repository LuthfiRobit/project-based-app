<script>
    const table = $('#example').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('master.iuran.list') }}', // Ganti endpoint ke iuran
            type: 'GET',
            data: function(d) {
                d.filter_status = $('#filter_status').val();
                d.filter_tahun = $('#filter_tahun').val();
            }
        },
        columns: [{
                data: 'checkbox',
                orderable: false,
                searchable: false
            },
            {
                data: 'aksi',
                orderable: false,
                searchable: true
            },
            {
                data: 'nama_tahun_pelajaran', // Relasi dari model
                orderable: false,
                searchable: true
            },
            {
                data: 'nama_iuran',
                orderable: false,
                searchable: true
            },
            {
                data: 'nominal_iuran',
                orderable: false,
                searchable: true
            },
            {
                data: 'status',
                orderable: false,
                searchable: false
            }
        ],
        language: {
            searchPlaceholder: "Cari (min 4)...",
            search: ''
        }
    });

    const performOptimizedSearch = _.debounce(query => {
        if (query.length >= 4 || query.length === 0) table.search(query).draw();
    }, 3000);

    $('#example_filter input').unbind().on('input', function() {
        performOptimizedSearch($(this).val());
    });

    $('#filter_status').change(() => table.ajax.reload());
    $('#filter_tahun').change(() => table.ajax.reload());

    $('#selectAll').click(function() {
        $('.table-checkbox:not(:disabled)').prop('checked', this.checked);
    });
</script>
