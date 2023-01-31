<?php

namespace App\Policies;

use App\Models\Infopage;
use App\Models\User;

class InfopagePolicy
{
    public function before(User $user, $ability) {
        // @HOOK_POLICY_BEFORE
        if($user->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(User $user) {
        // @HOOK_POLICY_VIEW
        return $user->hasPermissionTo('infopages.view', request()->whereIam());
    }

    public function create(User $user) {
        // @HOOK_POLICY_CREATE
        return $user->hasPermissionTo('infopage.create', request()->whereIam());
    }

    public function update(User $user, Infopage $chInfopage) {
        // @HOOK_POLICY_UPDATE
        if( !$user->hasPermissionTo('infopage.update', request()->whereIam()) )
            return false;
        return true;
    }

    public function delete(User $user, Infopage $chInfopage) {
        // @HOOK_POLICY_DELETE
        if( !$user->hasPermissionTo('infopage.delete', request()->whereIam()) )
            return false;
        return true;
    }

    // @HOOK_POLICY_END


}
