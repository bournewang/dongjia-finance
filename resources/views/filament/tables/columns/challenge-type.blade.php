<?php use App\Models\Challenge;?>
<span class="{{[
    Challenge::TYPE_CONSUMER    => 'text-gray-600',
    Challenge::TYPE_CAR_OWNER   => 'text-primary-600',
    Challenge::TYPE_CAR_MANAGER => 'text-danger-600',
][$getState()]}}">
    {{Challenge::typeOptions()[$getState()]}}
</span>
