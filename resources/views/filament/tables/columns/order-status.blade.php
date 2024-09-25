<?php use App\Models\Order;?>
<span class="{{[
    Order::CREATED      => 'text-gray-600',
    Order::PAID   => 'text-primary-600',
    Order::CANCELED       => 'text-danger-600',
    Order::REFUNDED      => 'text-danger-600'
][$getState()]}}">
    {{Order::statusOptions()[$getState()]}}
</span>
