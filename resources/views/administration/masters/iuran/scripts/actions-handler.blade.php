<script>
    $('#example').on('click', '.dropdown-item', function() {
        const action = $(this).data('action');
        const dataId = $(this).data('id');
        const url = '{{ route('master.iuran.show', ':id') }}'.replace(':id', dataId);

        if (!dataId) return ResponseHandler.handleError("ID tidak ditemukan!");

        const handlers = {
            'action_show': handleShow,
            'action_edit': handleEdit,
            // Tambahkan case baru di sini
        };

        if (handlers[action]) {
            AjaxHandler.sendGetRequest(url, response => {
                if (response.status === 200 && response.data) {
                    handlers[action](response.data);
                } else {
                    ResponseHandler.handleError("Data tidak ditemukan.");
                }
            });
        }
    });

    function handleShow(data) {
        $('#detail_nama_tahun_pelajaran').text(data.nama_tahun_pelajaran || 'N/A');
        $('#detail_nama_iuran').text(data.nama_iuran || 'N/A');
        $('#detail_nominal_iuran').text(data.nominal_iuran ? formatRupiah(data.nominal_iuran) : 'N/A');
        $('#detail_status').text(data.status || 'N/A');
        $('#modalDetail').modal('show');
    }

    function handleEdit(data) {
        $('#editForm').attr('data-id', data.id_iuran);
        $('.selectpicker').selectpicker('refresh');
        $('#modalEdit').modal('show');
    }

    // Tambahkan handler tambahan jika ada case baru

    function formatRupiah(angka) {
        let number = parseInt(angka);
        if (isNaN(number)) return 'Rp. 0';
        return 'Rp. ' + number.toLocaleString('id-ID');
    }
</script>
