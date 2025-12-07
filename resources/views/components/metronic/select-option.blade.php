@props(['id', 'name', 'class'])

<select id="{{ $id ?? $name }}" name="{{ $name }}" class="form-select {{ $class ?? '' }} @error($name) is-invalid @enderror"
    data-control="select2" data-allow-clear="true" {{ $attributes }}>
    {{ $slot }}
</select>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
