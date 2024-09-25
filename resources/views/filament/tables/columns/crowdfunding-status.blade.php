<?php use App\Models\CrowdFunding;
$class = [
    CrowdFunding::APPLYING      => 'gray',
    CrowdFunding::WAITING       => 'warning',
    CrowdFunding::USING         => 'success',
    CrowdFunding::COMPLETED     => 'success',
    CrowdFunding::CANCELED      => 'danger'
][$getState()];
?>
<span class="text-custom-600 dark:text-custom-400" style="--c-400:var(--{{$class}}-400);--c-600:var(--{{$class}}-600);">
    {{CrowdFunding::statusOptions()[$getState()]}}
</span>
