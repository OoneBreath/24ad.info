@extends('front.layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>500</h1>
            <h2>{{ __('Internal Server Error') }}</h2>
            <p>{{ __('Something went wrong. Please try again later.') }}</p>
            <a href="{{ url('/') }}" class="btn btn-primary">{{ __('Back to Homepage') }}</a>
        </div>
    </div>
</div>
@endsection
