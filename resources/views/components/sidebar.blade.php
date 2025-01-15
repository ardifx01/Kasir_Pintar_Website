<div class="border-right d-flex flex-column flex-shrink-0 p-3 bg-light h-screen position-fixed justify-center " style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none justify-center w-full ">
        <object data="/storage/assets/icons/cash-machine.png" type="image/png" width="32" height="32" class="me-2 fill-white"></object>
        <span class="fs-4">KasirPintar</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        @foreach ($menuItems as $item)
            <li class="nav-link {{ in_array(Route::currentRouteName(), $item['activeRoute'])  ? 'bg-primary' : '' }}">
                <a href="{{ route($item['route']) }}" class="flex items-center p-2 w-full rounded-md text-gray-200 hover:bg-gray-700 hover:text-white no-underline {{ in_array(Route::currentRouteName(), $item['activeRoute']) ? 'text-white' : '' }}">
                    @svg($item['icon'],"w-20 me-2")
                    {{ $item['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
        <hr>
        <form method="POST" action="{{ route('logout') }}" class="flex justify-center">
            @csrf
            <button type="submit" class="btn btn-danger w-200">
                 @svg("ri-logout-circle-r-line","w-20 me-1 ")
                Logout
            </button>
        </form>
    </div>
