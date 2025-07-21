@extends('admin.master_layout')
@section('title')
    <title>{{ __('Liste de certificats') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Liste de certificats') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('admin.certificate.index') }}">{{ __('Liste de certificats') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Add Level') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Add new') }}</h4>
                                <div>
                                    <a href="{{ route('admin.certificate.index') }}" class="btn btn-primary"><i
                                            class="fa fa-arrow-left"></i>{{ __('Back') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.certificate.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 offset-md-2">
                                            <div class="form-group">
                                                <select name="user_id" id="user" class="form-control">
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name.' '.$user->last_name }}</option>)

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 offset-md-2">
                                            <div class="form-group">
                                                <select name="course_id" id="course" class="form-control">
                                                    @foreach ($courses as $course)
                                                        <option value="{{ $course->id }}">{{ $course->title }}</option>)

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 offset-md-2 upload">
                                                    <div class="from-group mb-3">
                                                        <label class="form-file-manager-label"
                                                            for="">{{ __('Certificat') }}
                                                            <code></code></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text" id="basic-addon1">
                                                                <a data-input="path" data-preview="holder"
                                                                    class="file-manager">
                                                                    <i class="fa fa-picture-o"></i> {{ __('Choose') }}
                                                                </a>
                                                            </span>
                                                            <input id="path" readonly
                                                                class="form-control file-manager-input" type="text"
                                                                name="upload_path">
                                                        </div>
                                                    </div>
                                                </div>
                                        <div class="text-center offset-md-2 col-md-8">
                                            <x-admin.save-button :text="__('Save')">
                                            </x-admin.save-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#user').on('change', function () {
        var userId = $(this).val();
        var courseSelect = $('#course');

        courseSelect.html('<option value="">Chargement...</option>');

        $.ajax({
            url: '/admin/certificate/user-courses/' + userId,
            method: 'GET',
            success: function (courses) {
                courseSelect.empty();
                if (courses.length > 0) {
                    $.each(courses, function (key, course) {
                        courseSelect.append('<option value="' + course.id + '">' + course.title + '</option>');
                    });
                } else {
                    courseSelect.append('<option value="">Aucun cours trouvé</option>');
                }
            },
            error: function () {
                courseSelect.html('<option value="">Erreur lors du chargement</option>');
            }
        });
    });
</script>
@endpush
