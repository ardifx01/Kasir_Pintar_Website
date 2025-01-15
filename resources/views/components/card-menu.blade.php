<a href="{{ route($routeName, $routeParameters) }}" class="text-decoration-none w-400">
    <div class="card text-center w-300" style="background-color: #F5F5F5; border-radius: 8px;">
        <div class="card-body">
            <img src="{{ asset('/storage/assets/icons/' . $img) }}" alt="{{ $label }}" class="img-fluid" style="height: 50px;">
            <div class="mt-2" style="font-size: 16px; color: #6B7280;">{{ $label }}</div>
        </div>
    </div>
</a>
