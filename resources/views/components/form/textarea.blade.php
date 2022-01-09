{{-- Template for textarea fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<textarea id="{{ $name }}" name="{{ $name }}" placeholder="{{ $placeholder }}" {{ $additional }}>{{ $content ?? old($name) }}</textarea>
@error($name)
    <span class="validation-error">{{ $message }}</span>
@enderror
