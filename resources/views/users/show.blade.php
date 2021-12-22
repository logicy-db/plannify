@extends('components.layouts.app')
@section('title', sprintf('Viewing: %s', $user->getFullname()))
@section('bodyClass', 'user-view-page')
@section('content')
    <div class="top-bar">
        <h2>{{ $user->getFullname() }}</h2>
        {{-- TODO: make editing button per form --}}
        <button class="edit-form">Edit user data</button>
    </div>

    <h2>User data</h2>
    <form class="form view-user" action="{{ route('users.update', $user->id) }}" method="POST">
        @method('PUT')
        @csrf
        <x-form.input name="email" type="email" placeholder="Email address" :inputValue="$user->email" :readonly="true"/>
        @if (Auth::id() === $user->id)
            <x-form.input name="current_password" type="password" placeholder="Current Password"/>
            <x-form.input name="new_password" type="password" placeholder="New Password"/>
            <x-form.input name="new_password_confirmation" type="password" placeholder="Confirm new password"/>
        @endif
        @if (sizeof($roleOptions))
            <x-form.select name="role_id" placeholder="Role" :options="$roleOptions" :selectValue="$user->role_id" :readonly="true"/>
        @endif
        <button type="submit">Submit</button>
    </form>
    {{--  TODO: user might lack profile  --}}
    <form class="form view-user" action="{{ route('profiles.update', $user->profile) }}" method="POST" enctype="multipart/form-data">
        <h2>Profile data</h2>
        @method('PUT')
        @csrf
        <div class="avatar-wrapper">
            <img class="avatar" src="{{ url($user->profile->getAvatarUrl()) }}" alt="Profile picture">
        </div>
        <label for="avatar">Profile picture</label>
        <input id="avatar" name="avatar" type="file" accept="image/*">
        <x-form.input name="first_name" type="text" placeholder="First name" :inputValue="$user->profile->first_name" :readonly="true"/>
        <x-form.input name="last_name" type="text" placeholder="Last name" :inputValue="$user->profile->last_name" :readonly="true"/>
        <x-form.input name="phone_number" type="tel" placeholder="Phone number" :inputValue="$user->profile->phone_number" :readonly="true"/>
        <x-form.input name="address" type="text" placeholder="Address" :inputValue="$user->profile->address" :readonly="true"/>
        <button type="submit">Submit</button>
    </form>
    <script>
        $(document).ready(function () {
            let canEdit = false;

            $('.top-bar .edit-form').on('click', function () {
                canEdit = !canEdit;
                // TODO: Refactor
                if (canEdit) {
                    $(this).text('Disable editing');
                    $('.view-user input').attr('readonly', false);
                    $('.view-user select option').attr('hidden', false);
                    $('.view-user button').toggle();
                } else {
                    $(this).text('Enable editing');
                    $('.view-user input').attr('readonly', true);
                    $('.view-user select option:not(:selected)').attr('hidden', true);
                    $('.view-user button').toggle();
                }
            });

            function previewImage(input) {
                // TODO: refactor that later
                if (input.files && input.files[0]) {
                    let image = input.files[0];
                    console.log(image);
                    if (image.type.startsWith('image/')) {
                        let reader = new FileReader();
                        reader.onload = function (e) {
                            $('img.avatar').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(image);
                    } else {
                        $('img.avatar').attr('src', '');
                    }
                }
            }

            $('#avatar').change(function(){
                previewImage(this);
            });
        })
    </script>
@endsection
