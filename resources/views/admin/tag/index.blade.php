@extends('layouts.backend.app')
@section('title', 'Tag')
@push('css')
<link rel="stylesheet" href="{{asset('assets/backend')}}/css/bootstrap-toggle.min.css">

@endpush



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <a class="btn btn-primary waves-effect" href="{{ route('admin.tag.create') }}">
                <i class="material-icons">add</i>
                <span>Create Tag</span>
            </a>
        </div>
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            All Tags
                            <span class="badge bg-info">{{ $tags->count() }}</span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Post Count</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>
                                            <div class="custom-control custom-checkbox d-inline">
                                                <input type="checkbox" class="check-all custom-control-input" id="horizontalCheckbox" autocomplete="off">
                                                <label class="custom-control-label" for="horizontalCheckbox">Action</label>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($tags as $key=>$tag)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $tag->name }}</td>
                                        <td>{{ $tag->posts->count() }}</td>
                                        <td>{{ $tag->created_at }}</td>
                                        <td>{{ $tag->updated_at }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.tag.edit', $tag->id) }}" class="btn btn-info waves-effect">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button class="btn btn-danger waves-effect" type="button" onclick="deleteTag({{ $tag->id }})">
                                                <i class="material-icons">delete</i>
                                            </button>
                                            <form id="delete-form-{{ $tag->id }}" action="{{ route('admin.tag.destroy', $tag->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Exportable Table -->
    </div>
</section>
@endsection



@push('js')
<!-- Extra Data Tables -->
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>

<script src="{{asset('assets/backend')}}/js/bootstrap-toggle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    function deleteTag(id) {
        const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger"
        },
        buttonsStyling: false
        });
        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
            event.preventDefault();
            document.getElementById('delete-form-'+id).submit();
            swalWithBootstrapButtons.fire({
                title: "Deleted!",
                text: "Your file has been deleted.",
                icon: "success"
            });
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire({
                title: "Cancelled",
                text: "Your datais safe :)",
                icon: "error"
            });
        }
        });
    }
</script>




@endpush
