<?php

namespace App\Policies;

use App\Models\Module;
use App\Models\User;

class ModulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role->canAccessAdmin();
    }

    public function view(User $user, Module $module): bool
    {
        return $user->role->canAccessAdmin();
    }

    public function create(User $user): bool
    {
        return $user->role->canManageModules();
    }

    public function update(User $user, Module $module): bool
    {
        return $user->role->canManageModules();
    }

    public function delete(User $user, Module $module): bool
    {
        return $user->isSuperAdmin() && ! $module->is_core;
    }
}
