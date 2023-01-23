<?php

namespace App\Policies;

use App\Models\Infopage;

class InfopagePolicy
{
    public function before(Infopage $infopage, $ability) {
        // @HOOK_INFOPAGE_POLICY_BEFORE
        if($infopage->hasRole('Super Admin', 'admin') )
            return true;
    }

    public function view(Infopage $infopage) {
        // @HOOK_INFOPAGE_POLICY_VIEW
        return $infopage->hasPermissionTo('infopages.view', request()->whereIam());
    }

    public function create(Infopage $infopage) {
        // @HOOK_INFOPAGE_POLICY_CREATE
        return $infopage->hasPermissionTo('infopages.create', request()->whereIam());
    }

    public function update(Infopage $infopage, Infopage $chInfopage) {
        // @HOOK_INFOPAGE_POLICY_UPDATE
        if( !$infopage->hasPermissionTo('infopages.update', request()->whereIam()) )
            return false;
        if( $chInfopage->hasRole('Super Admin', 'admin'))
            return false;
        return true;
    }

    public function delete(Infopage $infopage, Infopage $chInfopage) {
        // @HOOK_INFOPAGE_POLICY_DELETE
        if( !$infopage->hasPermissionTo('infopages.delete', request()->whereIam()) )
            return false;
        if( $chInfopage->system )
            return false;
        return true;
    }

    // @HOOK_INFOPAGE_POLICY_END


}
