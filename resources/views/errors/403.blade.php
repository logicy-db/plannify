@extends('components.layouts.app')
@section('title', '403 Page')
@section('bodyClass', '403-page')
@section('content')
    <h2>403</h2>
    {{ $exception->getMessage() ?: 'Oops! Your are not authorized to do this action.' }}
@endsection
