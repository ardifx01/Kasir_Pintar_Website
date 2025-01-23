<div>
    @foreach($orderItems as $key => $item)
        <livewire:order-item :key="'order-'.$key.$item['quantity']" :id="$key" :product="$item['product']" :quantity="$item['quantity']" :subtotal="$item['subtotal']" />
    @endforeach
</div>
