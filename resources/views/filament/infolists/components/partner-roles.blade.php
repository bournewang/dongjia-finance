<?php if ($state && json_decode($state)) {
    foreach(json_decode($state) as $role){ ?>
<span>
    {{\App\Models\Company::partnerRoleOptions()[$role] ?? null}}
</span>
<?php }} ?>
