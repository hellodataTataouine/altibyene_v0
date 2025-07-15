@extends('admin.master_layout')
@section('title')
    <title>{{ __('Sessions du cours') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1 class="">{{ __('Sessions du cours') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </div>
                    <div class="breadcrumb-item">{{ __('Sessions du cours') }}</div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.course-session.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="form_padding">
                                    <div class="row">
                                        <div class="col-md-3 form-group">
                                            <input type="text" name="keyword" value="{{ request()->get('keyword') }}"
                                                class="form-control" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <input type="text" autocomplete="off" name="date" value="{{ request()->get('date') }}"
                                                class="form-control datepicker" placeholder="{{ __('Date') }}">
                                        </div>
                                       {{--  <div class="col-md-3">
                                            <div class="from-group">
                                                <select class="select2 form-group category" name="category">
                                                    <option value="">{{ __('Category') }}</option>
                                                    @foreach ($categories as $category)
                                                        <optgroup label="{{ $category->translation?->name }}">
                                                            @foreach ($category->subCategories as $subCategory)
                                                                <option value="{{ $subCategory->id }}"
                                                                    {{ request()->get('category') == $subCategory->id ? 'selected' : '' }}>
                                                                    {{ $subCategory->translation?->name }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}

                                        {{-- <div class="col-md-3 form-group">
                                            <select name="instructor" id="instructor" class="form-control select2">

                                                <option value="">{{ __('Instructor') }}</option>
                                                @foreach ($instructors as $instructor)
                                                    <option value="{{ $instructor->id }}" {{ request()->get('instructor') == $instructor->id ? 'selected' : '' }}>
                                                        {{ $instructor->name }} ({{ $instructor->email }})</option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                       {{--  <div class="col-md-3 form-group">
                                            <select name="approve_status" id="" class="form-control">
                                                <option value="">{{ __('Approval Status') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'pending' ? 'selected' : '' }}
                                                    value="pending">{{ __('Pending') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'approved' ? 'selected' : '' }}
                                                    value="approved">{{ __('Approved') }}</option>
                                                <option
                                                    {{ request()->get('approve_status') == 'rejected' ? 'selected' : '' }}
                                                    value="rejected">{{ __('Rejected') }}</option>
                                            </select>
                                        </div> --}}

                                        {{-- <div class="col-md-3 form-group">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">{{ __('Status') }}</option>
                                                <option {{ request()->get('status') == 'active' ? 'selected' : '' }}
                                                    value="active">{{ __('Published') }}</option>
                                                <option {{ request()->get('status') == 'inactive' ? 'selected' : '' }}
                                                    value="inactive">{{ __('Unpublished') }}</option>
                                                <option {{ request()->get('status') == 'draft' ? 'selected' : '' }}
                                                    value="draft">{{ __('Drafted') }}</option>
                                            </select>
                                        </div> --}}

                                        <div class="col-md-3 form-group">
                                            <select name="order_by" id="order_by" class="form-control">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="1" {{ request('order_by') == '1' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="0" {{ request('order_by') == '0' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <select name="par_page" id="par-page" class="form-control">
                                                <option value="">{{ __('Per Page') }}</option>
                                                <option value="10" {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('10') }}
                                                </option>
                                                <option value="50" {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('50') }}
                                                </option>
                                                <option value="100"
                                                    {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('100') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Courses List') }}</h4>
                                <div>
                                    <a href="{{ route('admin.course-session.create') }}" class="btn btn-primary"> <i
                                            class="fa fa-plus"></i>{{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th class="course-table-title">{{ __('Title') }}</th>
                                                <th>{{ __('Cours') }}</th>
                                                <th>{{ __('Max enrollment') }}</th>
                                                <th>{{ __('Enrolled students') }}</th>
                                                <th>{{ __('Session start date') }}</th>
                                                <th>{{ __('Session end date') }}</th>
                                                <th>{{ __('Session type') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($sessions as $session)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td class="course-table-title">
                                                        {{ $session->title }}
                                                    </td>
                                                    <td>
                                                        {{ $session->cours->title }}
                                                    </td>
                                                    <td>{{ $session->max_enrollments }}</td>
                                                    <td>
                                                       {{ $session->enrolled_students}}
                                                    </td>
                                                    <td>
                                                        {{ formatDate($session->start_date) }}
                                                    </td>
                                                    <td>
                                                        {{ formatDate($session->end_date) }}
                                                    </td>
                                                    <td>{{ __($session->type) ?? '--' }}</td>
                                                    <td class="text-center">
                                                        <div>
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle"
                                                                    type="button" id="dropdownMenu2" data-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fa fa-ellipsis-v"></i>
                                                                </button>
                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                                                    <a href="{{ route('admin.course-session.edit', $session->id) }}"
                                                                        class="dropdown-item"
                                                                        >{{ __('Edit') }}</a>

                                                                    <a href="{{ route('admin.course-session.destroy', $session->id) }}"
                                                                        class="dropdown-item text-danger delete-item">{{ __('Delete') }}</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Couse Filter')" route="admin.course-session.create"
                                                    create="no" :message="__('No data found!')" colspan="11"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $sessions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@push('js')
    <script src="{{ asset('global/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('backend/js/default/course-sessions.js') }}"></script>
    <script src="{{ asset('backend/js/sweetalert.js') }}"></script>
@endpush
