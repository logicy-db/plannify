{{-- Template for user form fields --}}
<label for="{{ $name }}">{{ $label }}</label>
<div class="select-wrapper">
    <select id="{{ $name }}" name="{{ $name }}">
        {{-- TODO: Refactor --}}
        @foreach($options as $value => $name)
            @if ($readonly)
                @if ($value === $selectValue)
                    <option value="{{ $value }}" selected>{{ $name }}</option>
                @else
                    <option value="{{ $value }}" hidden>{{ $name }}</option>
                @endif
            @else
                @if ($value === $selectValue)
                    <option value="{{ $value }}" selected>{{ $name }}</option>
                @else
                    <option value="{{ $value }}">{{ $name }}</option>
                @endif
            @endif
        @endforeach
    </select>
    <i class="fas fa-caret-down"></i>
</div>
@error($name)
<span class="validation-error">{{ $message }}</span>
@enderror

