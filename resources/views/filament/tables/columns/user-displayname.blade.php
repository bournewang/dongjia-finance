<?php $u = \App\Models\User::find($getState());?>

<span>
    {{$u->displayName()}}
</span>
