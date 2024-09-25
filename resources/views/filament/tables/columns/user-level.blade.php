<?php use App\Models\User;?>
<span>
    {{User::levelOptions()[$getState()] ?? null}}
</span>
