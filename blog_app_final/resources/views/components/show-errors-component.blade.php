@foreach ($errors->get($name) as $error) 
    <span class="text-danger">{{ $error }}</span>
@endforeach