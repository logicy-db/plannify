@extends('components.layouts.app')
@section('title', sprintf('Viewing: %s', $user->getFullname()))
@section('bodyClass', 'user-view-page')
@section('content')
    <div class="top-bar">
        <h2>{{ $user->getFullname() }}</h2>
        <button class="alert edit-form">
            <span class="start-editing">Edit {{ auth()->id() == $user->id ? 'my' : 'user' }} data</span>
            <span class="stop-editing">Disable editing</span>
        </button>
    </div>
    @can('update', $user)
        <h2>Account data</h2>
        <form class="form account-form" action="{{ route('users.update', $user->id) }}" method="POST">
            @method('PUT')
            @csrf
            <x-form.input name="email" type="email" placeholder="Email address" :inputValue="$user->email" :readonly="true"/>
            @if (Auth::id() === $user->id)
                <div class="password-section">
                    <div class="new-password-toggler">
                        <x-form.input name="set-new-password" type="checkbox" placeholder="Set new password?" label="Set new password?"/>
                    </div>
                    <div class="new-password">
                        <x-form.input name="new_password" type="password" placeholder="New Password"/>
                        <x-form.input name="new_password_confirmation" type="password" placeholder="Confirm new password"/>
                    </div>
                    <x-form.input name="current_password" type="password" placeholder="Current Password"/>
                </div>
            @endif
            @if (sizeof($roleOptions) && Auth::id() !== $user->id)
                <x-form.select name="role_id" placeholder="Role" :options="$roleOptions" :selectValue="$user->role_id" :readonly="true"/>
            @else
                <x-form.input placeholder="Your role" name="user_role" type="text" :readonly="true" :inputValue="$user->role->name" additional="disabled"/>
            @endif
            <button class="success" type="submit">Submit</button>
        </form>
    @endcan
    @can('update', $user->profile)
        <h2>Profile data</h2>
        @if ($user->profile)
            <form class="form profile-form" action="{{ route('profiles.update', $user->profile) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="avatar-wrapper">
                    <img class="avatar" src="{{ url($user->profile->getAvatarUrl()) }}" alt="Profile picture">
                </div>
                <div class="avatar-input">
                    <label for="avatar">Profile picture</label>
                    <input id="avatar" name="avatar" type="file" accept="image/*">
                </div>
                <x-form.input name="first_name" type="text" placeholder="First name" :inputValue="$user->profile->first_name" :readonly="true"/>
                <x-form.input name="last_name" type="text" placeholder="Last name" :inputValue="$user->profile->last_name" :readonly="true"/>
                <x-form.input name="phone_number" type="tel" placeholder="Phone number" :inputValue="$user->profile->phone_number" :readonly="true"/>
                <x-form.input name="address" type="text" placeholder="Address" :inputValue="$user->profile->address" :readonly="true"/>
                <button class="success" type="submit">Submit</button>
            </form>
        @else
            Your profile is not configured.
        @endif
    @endcan
    <script>
        $(document).ready(function () {
            let formEditingClass = 'form-being-edited';
            $('.top-bar .edit-form').on('click', function () {
                if ($(this).toggleClass(formEditingClass).hasClass(formEditingClass)) {
                    $('.form input').attr('readonly', false);
                    $('.form select option').attr('hidden', false);
                } else {
                    $('.form input').attr('readonly', true);
                    $('.form select option:not(:selected)').attr('hidden', true);
                }
                $('.form button').toggle();
                $('.account-form .password-section').toggle();
                $('.profile-form .avatar-input').toggle();
            });

            $('#set-new-password').on('click', function () {
                $('.new-password').toggle();
            });

            $('#avatar').change(function(){
                previewImageOnUpload(this, $('img.avatar'));
            });
        });
    </script>
@endsection
