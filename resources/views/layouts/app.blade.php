@extends('layouts.skeleton')

@section('app')
    @if(auth()->check())
        @include('layouts.nav')
    @endif

    <main class="py-4">
        @yield('content')
    </main>
@endsection
