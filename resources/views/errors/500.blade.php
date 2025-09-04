@extends('errors::layout')

@section('title', __('Server Error'))

@section('message')
    <div>
        <strong>Whoops!</strong> Something went wrong.<br>
        <span>Please try again later or <a href="{{ url('/') }}">return to homepage</a>.</span>
        <br><br>
        <strong>Error code:</strong> {{ $exception->getMessage() }}<br>
    </div>
@endsection
