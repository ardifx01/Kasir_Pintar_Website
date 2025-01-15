<div class="mb-3">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <div class="input-group">
        <input type="{{ $inputType }}" id="{{ $id }}" name="{{ $name }}" class="form-control @error($name) is-invalid @enderror" placeholder="{{ $placeholder ?? ''}}">
        <button class="btn btn-outline-light border" type="button" wire:click="togglePassword">

            <img src="{{ $imagePath }}" alt="Toggle Password" width="20">

        </button>
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
