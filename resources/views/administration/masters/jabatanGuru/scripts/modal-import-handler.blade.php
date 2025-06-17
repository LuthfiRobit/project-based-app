<script>
    // Reset form input ketika modal ditutup
    $('#importModal').on('hidden.bs.modal', function() {
        $('#importForm')[0].reset();
    });

    $("#importForm").on("submit", function(e) {
        e.preventDefault();

        const fileInput = $("#fileInput")[0];
        const file = fileInput.files[0];

        if (!file) {
            Swal.fire({
                title: 'Peringatan',
                text: 'Silakan pilih file untuk diunggah.',
                icon: 'warning'
            });
            return;
        }

        const allowedExtensions = ['xlsx', 'xls'];
        const fileExtension = file.name.split('.').pop().toLowerCase();

        if (!allowedExtensions.includes(fileExtension)) {
            Swal.fire({
                title: 'Peringatan',
                text: 'File harus memiliki ekstensi .xlsx atau .xls.',
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Apa anda yakin?',
            text: "Apakah file anda sudah benar dan sesuai?",
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
        }).then((result) => {
            if (result.value) {
                const action =
                    "{{ route('master.jabatan-guru.import-excel') }}"; // Pastikan ini adalah URL yang benar
                const formData = new FormData();
                formData.append("file", file);

                Swal.fire({
                    title: 'Mengimpor Data',
                    html: 'Proses impor data sedang berlangsung...',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        Swal.close();

                        if (response.success) {
                            let message = '<strong>Data berhasil disimpan!</strong><br>';

                            // Tampilkan data yang berhasil
                            if (response.successes && response.successes.length > 0) {
                                message +=
                                    '<strong>Data yang berhasil disimpan:</strong><br>';
                                response.successes.forEach((success, index) => {
                                    message +=
                                        `Baris ${index + 1} -> ${success.nama_jabatan}<br>`;
                                });
                            }

                            // Tampilkan data yang gagal
                            if (response.failures && response.failures.length > 0) {
                                message +=
                                    '<br><strong>Data yang gagal disimpan:</strong><br>';
                                response.failures.forEach(failure => {
                                    message += `Baris ${failure.row_number} -> `;
                                    message += failure.errors.join(' | ') + '<br>';
                                });
                            }

                            Swal.fire({
                                title: 'Hasil Import',
                                html: message,
                                icon: 'success'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    $('#modalImport').modal('hide');
                                    table.ajax.reload();
                                }
                            });

                        } else {
                            Swal.fire({
                                title: 'Oops...',
                                text: response.message ||
                                    'Terjadi kesalahan tidak terduga.',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(error) {
                        Swal.close();
                        Swal.fire({
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengirim data',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
</script>
