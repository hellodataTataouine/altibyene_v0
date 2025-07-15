@extends('admin.master_layout')
@section('title')
    <title>{{ __('Demande de livre') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Demandes de livre') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Nom et prénom') }}</th>
                                                <th>{{ __('Téléphone') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Message') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($requests as $index => $request)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $request->name }}</td>
                                                    <td>{{ $request->phone }}</td>
                                                    <td>{{ $request->email }}</td>
                                                    <td>{{ $request->message }}</td>
                                                    <td>{{ $request->created_at->format('d-m-Y H:i') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.askbook.show', $request->id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                    {{-- <td>
                                                        @if ($message->status == 0)
                                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                        @elseif($message->status == 1)
                                                            <span class="badge badge-success">{{ __('Approved') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <form action="{{ route('admin.course-delete-request.update', $message->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="d-flex">
                                                                <select name="action" id="" class="form-control">
                                                                    <option value="">{{ __('Select') }}</option>
                                                                    <option @selected($message->status == 0) value="active">{{ __('Active') }}</option>
                                                                    <option @selected($message->status == 1) value="inactive">{{ __('Inactive') }}</option>
                                                                </select>
                                                                <button type="submit" class="btn btn-primary course-delete-btn">{{ __('Update') }}</button>
                                                            </div>
                                                        </form>
                                                    </td> --}}
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Demandes de livre')" route="" create="no"
                                                    :message="__('No data found!')" colspan="5"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
