
@extends('frontend.basics.master')


@section('body')

    <style>
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .btn-add-staff {
            background-color: #28a745;
            color: white;
        }
        .btn-add-staff:hover {
            background-color: #218838;
            color: white;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .table tbody tr:nth-child(even) {
            background-color: #ffffff;
        }


    </style>

    <section  style="width: 80%; margin: 0 auto;">


        <div class="container my-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Staff Info</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-add-staff" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fas fa-plus"></i> Add Staff Info
                        </button>
                    </div>
                    <div class="">
                        <h1>Staff List</h1>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="staffDataTable">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Salary</th>
                                    <th>Created at</th>
                                    <th>Action</th> <!-- If you added an action column -->
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Data will be populated by DataTables -->
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>








    <!-- Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add Staff Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('staff.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="staffName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="staffName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="staffEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="staffEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="staffPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="staffPhone" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="staffSalary" class="form-label">Salary</label>
                            <input type="number" step="0.01" class="form-control" id="staffSalary" name="salary" required>
                        </div>
                        <div class="mb-3">
                            <label for="staffDate" class="form-label">Date</label>
                            <input type="date" class="form-control" id="staffDate" name="date" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="staffId" name="staffId">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div
@endsection

@section('js')

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#staffDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('staff.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'salary', name: 'salary' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            // Edit button click handler
            $('#staffDataTable').on('click', '.btn-edit', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: '{{ route("get-staff-response", "") }}/' + id,
                    type: 'GET',
                    success: function(data) {
                        $('#staffId').val(data.id);
                        $('#name').val(data.name);
                        $('#email').val(data.email);
                        $('#editModal').modal('show');
                    },
                    error: function() {
                        Swal.fire('Error', 'Unable to fetch staff details.', 'error');
                    }
                });
            });

            // Edit form submit handler
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route("update-staff-response", "") }}/' + $('#staffId').val(),
                    type: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editModal').modal('hide');
                        table.ajax.reload(); // Refresh DataTable
                        Swal.fire('Success', 'Staff details updated successfully.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'Unable to update staff details.', 'error');
                    }
                });
            });

            // Delete button click handler
            $('#staffDataTable').on('click', '.btn-delete', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("delete-staff-response", "") }}/' + id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                table.ajax.reload(); // Refresh DataTable
                                Swal.fire('Deleted!', 'Staff has been deleted.', 'success');
                            },
                            error: function() {
                                Swal.fire('Error', 'Unable to delete staff.', 'error');
                            }
                        });
                    }
                });
            });
        });

    </script>


@endsection
