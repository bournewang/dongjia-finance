<?php use App\Models\Agent;
$class = [
    Agent::APPLYING => 'gray',
    Agent::APPROVED => 'success',
    Agent::REJECTED => 'danger'
][$state];
?>
<span class="text-custom-600 dark:text-custom-400" style="--c-400:var(--{{$class}}-400);--c-600:var(--{{$class}}-600);">
    {{Agent::statusOptions()[$state]}}
</span>
