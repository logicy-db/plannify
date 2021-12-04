@extends('components.layouts.app')
@section('title', sprintf('Viewing: %s', $user->getFullname()))
@section('bodyClass', 'user-view-page')
@section('content')
    <div class="top-bar">
        <h2>User: {{ $user->getFullname() }}</h2>
        <button class="edit-form">Enable editing</button>
    </div>
    <form class="form view-user" action="{{ route('admin.user.edit', $user->id) }}"  method="POST">
        @csrf
        <x-form.input name="email" type="email" placeholder="Email address" :inputValue="$user->email" :readonly="true"/>
        <x-form.input name="firstname" type="text" placeholder="First name" :inputValue="$user->first_name" :readonly="true"/>
        <x-form.input name="lastname" type="text" placeholder="Lastname" :inputValue="$user->last_name" :readonly="true"/>
        <x-form.select name="role_id" placeholder="Role" :options="$roleOptions" :selectValue="$user->role_id" :readonly="true"/>
        <button type="submit">Edit</button>
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
        })
    </script>
@endsection
