<script>
    $(document).on('change', '.datatable-status-switcher', function(e) {
        const checkbox = $(this);
        const id = checkbox.data('id');
        const isChecked = checkbox.is(':checked');

        // Cegah perubahan status
        e.preventDefault();
        e.stopImmediatePropagation();

        // Kembalikan nilai ke posisi sebelumnya (karena perubahan dicegah)
        checkbox.prop('checked', !checkbox.is(':checked'));

        // Tampilkan pesan
        Swal.fire({
            title: 'Aksi Tidak Diperbolehkan',
            text: "Status tahun pelajaran tidak dapat diubah langsung. Silakan aktifkan semester untuk mengatur tahun pelajaran aktif.",
            icon: 'info',
            confirmButtonText: 'Oke',
        });

        // // Hanya jalankan jika diaktifkan (aktifkan tahun pelajaran)
        // if (!isChecked) {
        //     checkbox.prop('checked', true); // Batalkan jika dimatikan
        //     return;
        // }else{

        // }

        // const newStatus = 'active';
        // const url = '{{ route('master.tahun-pelajaran.update-status-single') }}';

        // Swal.fire({
        //     title: 'Aktifkan Tahun Pelajaran?',
        //     text: "Apakah Anda yakin ingin mengaktifkan tahun pelajaran ini? Tahun pelajaran lain akan dinonaktifkan.",
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonText: 'Ya, Aktifkan!',
        //     cancelButtonText: 'Batal'
        // }).then(result => {
        //     if (result.isConfirmed) {
        //         const originalState = checkbox.prop('checked');

        //         AjaxHandler.sendRequest(
        //             url, 'POST', JSON.stringify({
        //                 id: id,
        //                 status: newStatus
        //             }),
        //             () => {
        //                 table.ajax.reload(); // Reload DataTable
        //             },
        //             xhr => {
        //                 const errors = xhr.responseJSON?.data || {};
        //                 ResponseHandler.handleValidationErrors(errors, '');
        //                 checkbox.prop('checked', !originalState);
        //             },
        //             null, {
        //                 'Content-Type': 'application/json'
        //             }
        //         );
        //     } else {
        //         checkbox.prop('checked', false); // Batalkan perubahan
        //     }
        // });
    });
</script>
