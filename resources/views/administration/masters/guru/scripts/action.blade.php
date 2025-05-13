<script>
    $('.selectpicker').selectpicker();

    // Fungsi untuk menangani klik pada item dropdown Aksi
    $('#example').on('click', '.dropdown-item', function() {
        var action = $(this).data('action'); // Mendapatkan aksi yang dipilih
        var dataId = $(this).data('id'); // Mendapatkan id_siswa_baru
        var url = '{{ route('master.guru.show', ':id') }}'.replace(':id', dataId);
        var baseUrl = "{{ asset('') }}";

        // Validasi: Cek apakah ID ditemukan
        if (!dataId) {
            ResponseHandler.handleError("ID tidak ditemukan!");
            return;
        }

        // Cek action dan tampilkan modal yang sesuai
        switch (action) {
            case 'action_show':
                // Mengirim permintaan AJAX untuk mendapatkan data guru
                AjaxHandler.sendGetRequest(url, function(response) {
                    if (response.status === 200 && response.data) {
                        console.log(response.data);

                        // Isi modal dengan data guru
                        $('#detail_nama_guru').text(response.data.nama_guru || 'N/A');
                        $('#detail_nip').text(response.data.nip || 'N/A');
                        $('#detail_nama_jabatan').text(response.data.jabatan ||
                            'N/A'); // Asumsi jabatan relasi
                        $('#detail_jenis_kelamin').text(response.data.jenis_kelamin === 'L' ?
                            'Laki-laki' : 'Perempuan');
                        $('#detail_tanggal_lahir').text(response.data.tanggal_lahir || 'N/A');
                        $('#detail_alamat').text(response.data.alamat || 'N/A');
                        $('#detail_no_telepon').text(response.data.no_telepon || 'N/A');
                        $('#detail_email').text(response.data.email || 'N/A');
                        $('#detail_pendidikan_terakhir').text(response.data.pendidikan_terakhir ||
                            'N/A');
                        $('#detail_status_guru').text(response.data.status_guru || 'N/A');
                        $('#detail_status_pernikahan').text(response.data.status_pernikahan || 'N/A');
                        $('#detail_tanggal_masuk').text(response.data.tanggal_masuk || 'N/A');
                        $('#detail_status').text(response.data.status || 'N/A');

                        // Handle guru photo
                        if (response.data.foto) {
                            // Combine the base URL and image path
                            let fotoPath = baseUrl + 'uploads/' + response.data.foto;

                            // Set the image source
                            $('#detail_foto').attr('src', fotoPath).show();
                        } else {
                            $('#detail_foto').hide(); // Hide the image if no photo
                        }

                        // Tampilkan modal
                        $('#modalDetail').modal('show');
                    } else {
                        ResponseHandler.handleError("Data tidak ditemukan.");
                    }
                });
                break;
            case 'action_edit':
                // Mendapatkan data untuk edit, bisa ditambahkan logika lainnya di sini
                AjaxHandler.sendGetRequest(url, function(response) {
                    if (response.status === 200 && response.data) {
                        //Arahkan ke route edit dengan membawa id 
                        window.location.href = '{{ route('master.guru.edit', ':id') }}'.replace(
                            ':id', dataId);
                    } else {
                        ResponseHandler.handleError("Data tidak ditemukan.");
                    }
                });
                break;
            default:
                break;
        }
    });
</script>
