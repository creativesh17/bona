@extends('layouts.backend.app')
@section('title', 'Create Category')
@push('css')

@endpush



@section('content')
<section class="content">
    <div class="container-fluid">
        <!-- Vertical Layout -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Edit Category
                            {{-- 9:40 --}}
                        </h2>
                    </div>
                    <div class="body">
                        <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <label for="name">Name</label>
                            <div class="form-group">
                                <div class="form-line">
                                    <input class="form-control" type="text" id="name" name="name" value="{{ $category->name }}" placeholder="Name">
                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" alert="role">
                                            <strong style="color: #f90c0c;">{{$errors->first('name')}}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group {{$errors->has('image') ? ' has-error' : ''}}">
                                <input type="file" name="image">
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" alert="role">
                                        <strong>{{$errors->first('image')}}</strong>
                                    </span>
                                @endif
                            </div>

                            <a class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.category.index') }}">BACK</a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Vertical Layout -->
    </div>
</section>

@endsection



@push('js')

@endpush
