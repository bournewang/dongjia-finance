<?php $u = \App\Models\User::find($state);?>

<span>
    {{$u->displayName()}}
</span>
