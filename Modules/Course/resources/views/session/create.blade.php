@extends('admin.master_layout')
@section('title')
    <title>{{ __('Sessions du cours') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('courses sessions') }}</h1>
            </div>

            <div class="section-body">
                <a href="{{ route('admin.course-session.index') }}" class="btn btn-primary"><i class="fas fa-list"></i>
                    {{ __('Course session list') }}</a>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.course-session.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group col-12">
                                            <label>{{ __('Course') }} <span class="text-danger">*</span></label>
                                            <select name="cours_id" class="form-control">
                                                @foreach ($courses as $cours)
                                                    <option value="{{ $cours->id }}">{{ $cours->title }}</option>
                                                @endforeach
                                            </select>

                                            @error('cours_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-12">
                                            <label>{{ __('Title') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="title" class="form-control" name="title">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label>{{ __('Session start date') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="start_date" class="form-control datepicker" name="start_date">
                                            @error('start_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label>{{ __('Session end date') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="end_date" class="form-control datepicker" name="end_date">
                                            @error('end_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label>{{ __('Session type') }} <span class="text-danger">*</span></label>
                                            <select name="type" class="form-control">
                                                <option value="Online">{{ __('Online') }}</option>
                                                <option value="Offline">{{ __('Offline') }}</option>
                                            </select>
                                            @error('type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label>{{ __('Max enrollment') }} <span class="text-danger">*</span></label>
                                            <input type="number" id="max_enrollments" class="form-control" name="max_enrollments" step="1">
                                            @error('max_enrollments')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary">{{ __('Save') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
