<div>
    <form action="{{  $action ? route($action) : route('products.index') }}" method="GET">
        <div class="flex items-center w-400">
            <select name="store_id" class="form-select w-full me-2">
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}" {{ (request('store_id') == $store->id || (isset($selectedStoreId) && $selectedStoreId == $store->id)) ? 'selected' : '' }}>{{ $store->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">
            @svg('ri-search-line','w-20')
            </button>
        </div>
    </form>
</div>
