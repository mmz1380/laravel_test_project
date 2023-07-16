@if (session()->has('message'))
    {{-- @dd(session('message')) --}}
    <div class="fixed top-0 left-1/2 tranform -translate-x-1/2 bg-laravel text-white px-30 py-3">
        <p>
            {{session('message')}}
        </p>
    </div>
@endif
