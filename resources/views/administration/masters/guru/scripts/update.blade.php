<script>
    $(document).ready(function() {
        // Inisialisasi selectpicker jika belum diinisialisasi
        $('.selectpicker').selectpicker();

        // Ambil ID dari URL
        var dataId = window.location.pathname.split('/').pop();
        var url = '{{ route('master.guru.show', ':id') }}'.replace(':id', dataId);

        // Mengirim permintaan AJAX untuk mendapatkan data guru
        AjaxHandler.sendGetRequest(url, function(response) {
            if (response.status === 200 && response.data) {
                console.log(response.data);

                $('#editForm').attr('data-id', response.data.id_guru);
                if (response.data.foto) {
                    // Generate the full image URL using Laravel's asset() helper (with Blade injection)
                    var fotoPath = "{{ asset('') }}" + 'uploads/' + response.data.foto;

                    // Create the 'Lihat Foto' link dynamically
                    var linkHTML = '<a href="' + fotoPath +
                        '" class="d-flex align-items-center" id="lihat_foto" target="_blank">' +
                        '<i class="fas fa-eye me-2"></i>' +
                        '<span>Lihat Foto</span>' +
                        '</a>';

                    // Insert the link into the container
                    $('#link-container').html(linkHTML);
                } else {
                    console.log('foto kosong');
                    // Ensure no link is present if no photo
                    $('#link-container').empty();
                }

                // Refresh selectpicker setelah perubahan nilai
                $('.selectpicker').selectpicker('refresh');
            } else {
                // Menangani error jika data tidak ditemukan
                ResponseHandler.handleError("Data tidak ditemukan.");
            }
        });
    });
</script>

<script>
    $("#editForm").on("submit", function(e) {
        e.preventDefault();
        let form = $(this);
        let dataId = $(this).attr('data-id');
        let url = `{{ route('master.guru.update', ':id') }}`.replace(':id', dataId);

        AjaxHandler.sendUpdateRequest(url, this, function() {
            // Redirect setelah berhasil
            window.location.href = "{{ route('master.guru.index') }}";
        }, function(response) {
            let errors = response.responseJSON?.data || {}; // Ensure an object is passed
            ResponseHandler.handleValidationErrors(errors, form);
        });
    });
</script>
