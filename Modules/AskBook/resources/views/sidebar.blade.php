@php
    $requests = \App\Models\BookRequest::where('is_readed',false)->get();
    //dd($requests->count());
@endphp
<li class="{{ isRoute('admin.askbook.*', 'active') }}">
    <a class="nav-link" href="{{ route('admin.askbook.index') }}" ><i class="fas fa-book"></i>
        <span class="{{ $requests->count() > 0 ? 'beep parent' : '' }}">{{ __('Demandes de livre') }}</span>
    </a>
</li>
