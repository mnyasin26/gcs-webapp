<form method="post" action="{{ route('flight-code.update', $id) }}">
    @csrf
    @method('patch')

    <x-checkbox-input id="{{ 'checkbox'.$id }}" name="selected"></x-checkbox-input>
</form>
