<?php use App\Models\Challenge;
$class = [
    Challenge::APPLYING     => 'gray',
    Challenge::CHALLENGING  => 'warning',
    // Challenge::USING         => 'success',
    Challenge::SUCCESS      => 'success',
    Challenge::CANCELED     => 'danger',
    Challenge::REJECTED     => 'danger'
][$getState()];
?>
<span class="text-custom-600 dark:text-custom-400" style="--c-400:var(--{{$class}}-400);--c-600:var(--{{$class}}-600);">
    {{Challenge::statusOptions()[$getState()]}}
</span>
