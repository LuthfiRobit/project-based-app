<script>
    // Fungsi untuk menangani klik tombol simpan pada modal permission
    $('#permissionForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the default way

        const roleId = $('#permissionForm').attr('data-id'); // Ambil role ID dari data-id pada form
        const selectedPermissions = getSelectedPermissions(); // Ambil ID permissions yang dipilih

        // Kirim data ke server menggunakan Ajax
        saveRolePermissions(roleId, selectedPermissions);
    });

    // Fungsi untuk mengumpulkan ID permission yang dipilih
    function getSelectedPermissions() {
        const selectedPermissions = [];
        const checkboxes = document.querySelectorAll('#permissions_list input[type="checkbox"]:checked');

        checkboxes.forEach(checkbox => {
            selectedPermissions.push(checkbox.value); // Ambil id_permission dari value checkbox
        });

        return selectedPermissions;
    }

    // Fungsi untuk mengirimkan data role_permission ke server
    function saveRolePermissions(roleId, permissions) {
        const url = '{{ route('rbac.role.store-role-permission', ':id') }}'.replace(':id',
            roleId); // URL untuk menyimpan role_permission

        // Siapkan data yang akan dikirim
        const data = {
            permissions: permissions, // Array permissions yang dipilih
            _token: '{{ csrf_token() }}' // CSRF token untuk keamanan
        };

        // Kirim data menggunakan Ajax
        $.ajax({
            url: url,
            method: 'POST', // Make sure to use POST method for saving permissions
            data: data, // Send data
            success: function(response) {
                if (response.status === 200) {
                    // Menyimpan berhasil, lakukan aksi sesuai kebutuhan
                    ResponseHandler.handleSuccess("Permissions role berhasil disimpan.");
                    $('#modalPermission').modal('hide'); // Tutup modal setelah sukses
                } else {
                    // Menangani error jika terjadi
                    ResponseHandler.handleError("Gagal menyimpan permissions.");
                }
            },
            error: function(xhr, status, error) {
                // Handle errors here, if any
                console.error("Error saving permissions:", error);
                ResponseHandler.handleError("Terjadi kesalahan saat menyimpan permissions.");
            }
        });
    }
</script>
