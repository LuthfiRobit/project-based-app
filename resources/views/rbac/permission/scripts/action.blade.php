<script>
    $('.selectpicker').selectpicker();

    // Fungsi untuk menangani klik pada item dropdown Aksi
    $('#example').on('click', '.dropdown-item', function() {
        var action = $(this).data('action'); // Mendapatkan aksi yang dipilih
        var dataId = $(this).data('id'); // Mendapatkan id_siswa_baru
        var url = '{{ route('rbac.permission.show', ':id') }}'.replace(':id', dataId);

        // Validasi: Cek apakah ID ditemukan
        if (!dataId) {
            ResponseHandler.handleError("ID tidak ditemukan!");
            return;
        }

        // Cek action dan tampilkan modal yang sesuai
        switch (action) {
            case 'action_show':
                AjaxHandler.sendGetRequest(url, function(response) {
                    if (response.status === 200 && response.data) {
                        $('#detail_permission_name').text(response.data.permission_name || 'N/A');
                        $('#detail_permission_description').text(response.data.permission_description ||
                            'N/A');
                        $('#modalDetail').modal('show');
                    } else {
                        ResponseHandler.handleError("Data tidak ditemukan.");
                    }
                });
                break;
            case 'action_edit':
                AjaxHandler.sendGetRequest(url, function(response) {
                    if (response.status === 200 && response.data) {
                        // Set the data-id attribute of the editForm
                        $('#editForm').attr('data-id', response.data.id_permission);
                        $('.selectpicker').selectpicker(
                            'refresh'); // Refresh selectpicker after changing the value
                        // Show the modal for editing
                        $('#modalEdit').modal('show');
                    } else {
                        ResponseHandler.handleError("Data tidak ditemukan.");
                    }
                });
                break;
            default:
                break;
        }
    });

    $('.btn-update-status').click(function() {
        // Get the status from the button's data-status attribute
        const status = $(this).data('status');
        const url = '{{ route('master.jabatan-guru.update-status-multiple') }}';

        // Collect selected IDs from checkboxes
        let selectedIds = [];
        $('.table-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });

        // Check if any IDs are selected
        if (selectedIds.length === 0) {
            ResponseHandler.handleError('Anda harus memilih setidaknya satu data terlebih dahulu.');
            return;
        }

        // Prepare the data to send
        const data = {
            ids: selectedIds,
            status: status
        };

        // Send the AJAX request using the global handler
        AjaxHandler.sendRequest(
            url, // URL
            'POST', // Method
            JSON.stringify(data), // Data payload (dikonversi ke JSON)
            function() {
                $('#example').DataTable().ajax.reload();
            },
            function(xhr) {
                // Error callback
                ResponseHandler.handleError(xhr.responseJSON?.message ||
                    'Terjadi kesalahan saat memperbarui status.');
            },
            null, // Form (tidak diperlukan karena menggunakan JSON)
            {
                'Content-Type': 'application/json' // Set header Content-Type
            }
        );
    });
</script>
