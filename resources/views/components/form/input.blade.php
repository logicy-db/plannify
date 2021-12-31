{{-- Template for input fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" placeholder="{{ $placeholder }}"
       value="{{ $inputValue ?? old($name) }}" {{ $readonly }} {{ $additional }}>
@error($name)
    <span class="validation-error">{{ $message }}</span>
@enderror
