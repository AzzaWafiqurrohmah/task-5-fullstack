@push('script')
    <script>
        const categoryTable = $('#categories-table').DataTable({
            serverSide: true,
            rendering: true,
            ajax: '{{ route('categories.datatables') }}',
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false,},
                {data: 'name', name: 'name'},
                {data: 'action', orderable: false, searchable: false},
            ],
        });

        const categoryModal = new bootstrap.Modal('#category-modal');
        let editID = 0;

        function fillForm() {
            $.ajax({
                url: `/categories/${editID}`,
                success: (res) => fillFormdata(res.data),
            });
        }

        function saveItem() {
            const url = editID != 0 ?
                `/categories/${editID}/update` :
                `/categories/store`;

            const method = editID != 0 ? 'PUT' : 'POST';

            $.ajax({
                url,
                method,
                data: $('#category-form').serialize(),
                success(res) {
                    categoryTable.ajax.reload();
                    categoryModal.hide();


                    Swal.fire({
                        icon: 'success',
                        text: res.meta.message,
                        timer: 1500,
                    });
                },
                error(err) {
                    if(err.status == 422) {
                        displayFormErrors(err.responseJSON.data);
                        return;
                    }

                    Swal.fire({
                        icon: 'error',
                        text: 'There was something wrong!',
                        timer: 1500,
                    });
                },
            });
        }

        function deleteItem(id) {
            $.ajax({
                url: `/categories/${id}`,
                method: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success(res) {
                    categoryTable.ajax.reload();

                    Swal.fire({
                        icon: 'success',
                        text: res.meta.message,
                        timer: 1500,
                    });
                },
                error(err) {
                    Swal.fire({
                        icon: 'error',
                        text: 'There was something wrong!',
                        timer: 1500,
                    });
                },
            });
        }

        $('#category-modal').on('show.bs.modal', function (event) {
            $('#category-modal-title').text(editID ? 'Edit Kategori' : 'Tambah Kategori');
            if(editID != 0)
                fillForm();
        });

        $('#category-modal').on('hidden.bs.modal', function (event) {
            editID = 0;

            removeFormErrors();
            $('#category-form').trigger('reset');
        });

        $('#category-form').submit(function(e) {
            e.preventDefault();

            removeFormErrors();
            saveItem();
        });

        $('#categories-table').on('click', '.btn-edit', function(e) {
            editID = this.dataset.id;
            categoryModal.show();
        });

        $('#newCategory').on('click', function (e) {
            categoryModal.show();
        });


        $('#categories-table').on('click', '.btn-delete', function(e) {
            Swal.fire({
                icon: 'question',
                text: 'Are you sure will delete this?',
                showCancelButton: true,
                cancelButtonText: 'Batal',
            }).then((res) => {
                if(res.isConfirmed)
                    deleteItem(this.dataset.id);
            });
        });
        
    </script>
@endpush