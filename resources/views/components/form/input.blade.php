{{-- Template for user form fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
       value="{{ $inputValue }}" {{ $readonly }}>
@error($name)
    <span class="validation-error">{{ $message }}</span>
@enderror
