@props([
    'id'          => null,
    'type'        => 'text',
    'name',
    'value'       => '',
    'placeholder' => 'Complete the field',
    'required'    => false,
    'step'        => null,
    'maxlength'   => null, // < == make it dynamic
    'error'       => null,
])


@php
    $inputClasses = 'form-control form-control-sm';
    if ($error) {
        $inputClasses .= ' is-invalid';
    }
@endphp

<input id="{{ $id ?? $name }}" class="{{ $inputClasses }}" type="{{ $type }}" name="{{ $name }}"
    value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}"
    {{ $required ? 'required' : '' }} {{ $step ? "step=$step" : '' }} {{ $maxlength ? "maxlength=$maxlength" : '' }}>

@if ($error)
    <div class="invalid-feedback">
        {{ $error }}
    </div>
@endif
