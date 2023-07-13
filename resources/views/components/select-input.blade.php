@props(['disabled' => false, 'options' => [], 'value'])

<select x-data="{ value: '{{$value}}' }" x-model="value" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    <option selected>Select</option>
    @foreach($options as $key => $val)
        <option value="{{ $key }}">{{ $val }}</option>
    @endforeach
</select>
