{{-- Template for user form fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<div class="select-wrapper">
    <select id="{{ $name }}" name="{{ $name }}">
        {{-- TODO: Refactor --}}
        @foreach($options as $key => $value)
            @if ($key === $selectValue)
                <option value="{{ $key }}" selected>{{ $value }}</option>
            @else
                <option value="{{ $key }}" {{ $readonly ? 'hidden' : '' }}>{{ $value }}</option>
            @endif
        @endforeach
    </select>
    <i class="fas fa-caret-down"></i>
</div>
@error($name)
    <span class="validation-error">{{ $message }}</span>
@enderror

