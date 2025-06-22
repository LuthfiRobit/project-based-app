<script>
    $(document).on('change', '.datatable-status-switcher', function() {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const isChecked = checkbox.is(':checked');

        // Blok jika user mencoba menonaktifkan
        if (!isChecked) {
            checkbox.prop('checked', true); // Paksa tetap aktif
            return;
        }

        const newStatus = 'active';
        const url = '{{ route('master.semester.update-status-single') }}';

        Swal.fire({
            title: 'Aktifkan Semester?',
            text: "Apakah Anda yakin ingin mengaktifkan semester ini? SSemester lain akan dinonaktifkan secara otomatis. Tahun pelajaran induk juga akan diaktifkan jika belum aktif.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Aktifkan!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                const originalState = checkbox.prop('checked');

                AjaxHandler.sendRequest(
                    url,
                    'POST',
                    JSON.stringify({
                        id: id,
                        status: newStatus
                    }),
                    () => {
                        table.ajax.reload(); // Reload datatable
                    },
                    xhr => {
                        const errors = xhr.responseJSON?.data || {};
                        ResponseHandler.handleValidationErrors(errors, '');
                        checkbox.prop('checked', !
                            originalState); // Kembalikan ke kondisi awal jika gagal
                    },
                    null, {
                        'Content-Type': 'application/json'
                    }
                );
            } else {
                checkbox.prop('checked', false); // Batalkan perubahan jika user cancel
            }
        });
    });
</script>
