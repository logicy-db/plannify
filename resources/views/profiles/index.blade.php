@extends('components.layouts.app')
@section('title', 'Profile listing')
@section('bodyClass', 'profile-listing-page')
@section('content')
    <h2 class="text">Profiles</h2>
    <div class="search-bar">
        <form class="form profile-search">
            @csrf
            <input name="first_name" type="text" maxlength="50" placeholder="Search by first name"/>
            <input name="last_name" type="text" maxlength="50" placeholder="Search by lastname"/>
        </form>
        @csrf
    </div>
    <div class="profile-card-wrapper">
        @include('profiles.search')
    </div>
    <script>
        $(document).ready(function () {
            $("input[name='first_name'], input[name='last_name']").on('input', function () {
                $.ajax({
                    url: '{{ route('profiles.search') }}',
                    method: 'POST',
                    data: {
                        first_name: $("input[name='first_name']").val(),
                        last_name: $("input[name='last_name']").val(),
                        _token:$("input[name='_token']").val()
                    },
                    success: function (data) {
                        $('.profile-card-wrapper').fadeOut(500, function () {
                            $('.profile-card-wrapper').html(data).fadeIn();
                        });
                    }
                });
            });
        });
    </script>
@endsection
