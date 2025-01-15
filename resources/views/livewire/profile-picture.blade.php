<div>
    <div class="relative flex justify-center">
        <img src="{{ $photoUrl ?? asset('default-profile.jpg') }}" alt="Foto Profil" class="rounded-full w-32 h-32" width="80" wire:model="photoUrl">  <!-- default image -->
        <label for="photo" class="absolute inset-0 rounded-full cursor-pointer bg-gray-800 bg-opacity-50 flex items-center w-40 h-40 top-50 right-130 justify-center">
            <img src="{{asset('storage/assets/icons/camera.png')}}" alt="Upload Icon" class="h-6 w-6 text-white" width="20">
        </label>
        <input type="file" id="photo" wire:model="photo" class="hidden" name="url_image">
    </div>
</div>
