@extends('components.layouts.app')
@section('title', 'Profile listing')
@section('bodyClass', 'profile-listing-page')
@section('content')
    <h2 class="text">Profiles</h2>
    <div class="search-bar">
        @csrf
        <input class="search search-firstname" type="text" placeholder="Search by first name..." />
    </div>
    {{-- TODO: think of what sorting I really need --}}
    <div class="profile-card-wrapper">
        @include('profiles.search')
    </div>
    <script>
        $(document).ready(function () {
            $('.search-firstname').change(function () {
                $.ajax({
                    url: '{{ route('profiles.search') }}',
                    method: 'POST',
                    data: {first_name: $(this).val(), _token:$("input[name='_token']").val()},
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
