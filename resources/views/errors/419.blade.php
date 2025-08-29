@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Session Expired</h1>
    <p>Your session has expired due to inactivity. Please log in again.</p>
    <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
</div>
@endsection
