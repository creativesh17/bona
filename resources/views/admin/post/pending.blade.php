@extends('layouts.backend.app')
@section('title', 'Pending Posts')
@push('css')
{{-- <link rel="stylesheet" href="{{asset('assets/backend')}}/css/bootstrap-toggle.min.css"> --}}
<style>
    table thead th:nth-child(1) { width: 10%;  }
</style>
@endpush



@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <a class="btn btn-primary waves-effect" href="{{ route('admin.post.create') }}">
                <i class="material-icons">add</i>
                <span>Create Post</span>
            </a>
        </div>
        <!-- Exportable Table -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            All Posts
                            <span class="badge bg-info">{{ $posts->count() }}</span>
                        </h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable js-exportable" id="dataTable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th><i class="material-icons">visibility</i></th>
                                        <th>Is Approved ?</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($posts as $key=>$post)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ Str::limit($post->title, 10) }}</td>
                                        <td>{{ $post->user->name }}</td>
                                        <td>{{ $post->view_count }}</td>
                                        <td>
                                            @if ($post->is_approved == true)
                                                <span class="badge bg-blue">Approved</span>
                                            @else
                                                <span class="badge bg-pink">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($post->status == true)
                                                <span class="badge bg-blue">Published</span>
                                            @else
                                                <span class="badge bg-pink">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->created_at->toFormattedDateString() }}</td>
                                        <td class="text-center">
                                            @if ($post->is_approved == false)
                                            <button type="button" class=" btn btn-warning waves-effect" onclick="approvePost({{ $post->id }})">
                                                <i class="material-icons">done</i>
                                            </button>
                                            <form id="approval-form-{{ $post->id }}" action="{{route('admin.post.approve', $post->id)}}" method="POST" style="display: none;">
                                                @csrf
                                                @method('PUT')
                                            </form>
                                            @endif

                                            <a href="{{ route('admin.post.show', $post->id) }}" class="btn btn-green waves-effect">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="{{ route('admin.post.edit', $post->id) }}" class="btn btn-info waves-effect">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <button class="btn btn-danger waves-effect" type="button" onclick="deletePost({{ $post->id }})">
                                                <i class="material-icons">delete</i>
                                            </button>
                                            <form id="delete-form-{{ $post->id }}" action="{{ route('admin.post.destroy', $post->id) }}" method="POST" style="display: none;">
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

{{-- <script src="{{asset('assets/backend')}}/js/bootstrap-toggle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">

    function deletePost(id) {
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


    function approvePost(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
                });
                swalWithBootstrapButtons.fire({
                    title: "Are you sure?",
                    text: "You want to approve this post!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, approve it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    event.preventDefault();
                    document.getElementById('approval-form-'+id).submit();
                    swalWithBootstrapButtons.fire({
                        title: "Approved!",
                        text: "Your post has been approved!.",
                        icon: "success"
                    });
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelled",
                        text: "Your post is still pending :)",
                        icon: "info"
                    });
                }
            });
        }
</script>




@endpush
