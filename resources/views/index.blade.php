@extends('main')

@section('content')

    <div class="relative flex items-top justify-center min-h-screen backGround sm:items-center py-4 sm:pt-0">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <h1 class="text-center">Web Crawler</h1>

            {{ Form::open(array('route' => 'web_crawler')) }}

            {{ Form::label('text', 'URL to crawl : ') }}
            {{ Form::text('url', 'https://agencyanalytics.com', ['placeholder' => 'https://laravel.com']) }}

            {{ Form::label('text', 'Depth : ') }}
            {{ Form::text('depth', null) }}

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

            <div class="text-center">
                {{ Form::submit("Let's go!", ['class' => 'button buttonSubmit']) }}
            </div>

            {{ Form::close() }}

        </div>
    </div>
@endsection
