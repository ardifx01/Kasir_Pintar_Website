<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method === 'PUT' || $method === 'PATCH' || $method === 'DELETE')
        @method($method)
    @endif
    <div class="mb-3">
        {{ $slot }}
    </div>
    <button type="submit" class="btn btn-primary w-full">{{ $submitButtonText ?? 'Submit' }}</button>
</form>
