<?php use App\Models\User;
$ids = explode(',', $getState());?>
<span>
    {{implode(">", User::find($ids)->pluck('name')->toArray())}}
</span>
