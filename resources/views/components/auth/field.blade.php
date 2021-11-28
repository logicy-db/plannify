{{-- Template for authentification form fields --}}
<div>
    <label for="{{ $name }}">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
           @if ($isRequired) required @endif placeholder="{{ $placeholder }}" value="{{ old($name) }}">
    @error($name)
    <div>
        <span class="validation-error">{{ $message }}</span>
    </div>
    @enderror
</div>
