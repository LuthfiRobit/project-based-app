<script>
    $('.selectpicker').selectpicker();

    $('#example').on('click', '.dropdown-item', function() {
        const action = $(this).data('action');
        const dataId = $(this).data('id');
        const url = '{{ route('master.guru.show', ':id') }}'.replace(':id', dataId);

        if (!dataId) return ResponseHandler.handleError("ID tidak ditemukan!");

        const handlers = {
            'action_show': handleShow,
            'action_edit': handleEdit,
            // Tambahkan handler lain di sini
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
        const baseUrl = "{{ asset('') }}";

        $('#detail_nama_guru').text(data.nama_guru || 'N/A');
        $('#detail_nip').text(data.nip || 'N/A');
        $('#detail_nama_jabatan').text(data.jabatan || 'N/A');
        $('#detail_jenis_kelamin').text(data.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan');
        $('#detail_tanggal_lahir').text(data.tanggal_lahir || 'N/A');
        $('#detail_alamat').text(data.alamat || 'N/A');
        $('#detail_no_telepon').text(data.no_telepon || 'N/A');
        $('#detail_email').text(data.email || 'N/A');
        $('#detail_pendidikan_terakhir').text(data.pendidikan_terakhir || 'N/A');
        $('#detail_status_guru').text(data.status_guru || 'N/A');
        $('#detail_status_pernikahan').text(data.status_pernikahan || 'N/A');
        $('#detail_tanggal_masuk').text(data.tanggal_masuk || 'N/A');
        $('#detail_status').text(data.status || 'N/A');

        if (data.foto) {
            $('#detail_foto').attr('src', baseUrl + 'uploads/' + data.foto).show();
        } else {
            $('#detail_foto').hide();
        }

        $('#modalDetail').modal('show');
    }

    function handleEdit(data) {
        const editUrl = '{{ route('master.guru.edit', ':id') }}'.replace(':id', data.id_guru);
        window.location.href = editUrl;
    }

    // Tambahkan handler tambahan di sini jika ada action baru
</script>
