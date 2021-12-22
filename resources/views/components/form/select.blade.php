{{-- Template for user form fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<div class="select-wrapper">
    <select id="{{ $name }}" name="{{ $name }}">
        {{-- TODO: Refactor --}}
        @foreach($options as $value => $name)
            {{-- TODO: remove the code below--}}
            {{ $value }}
            {{ $selectValue }}
            @if ($value === $selectValue)
                <option value="{{ $value }}" selected>{{ $name }}</option>
            @else
                <option value="{{ $value }}" {{ $readonly ? 'hidden' : '' }}>{{ $name }}</option>
            @endif
        @endforeach
    </select>
    <i class="fas fa-caret-down"></i>
</div>
@error($name)
<span class="validation-error">{{ $message }}</span>
@enderror

