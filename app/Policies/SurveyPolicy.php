<?php

namespace App\Policies;

use App\Models\SurveySection;
use App\Models\User;

class SurveyPolicy
{
    /**
     * Only SuperAdmins and WardAdmins may manage the survey builder.
     * DataCollectors are always denied.
     */
    public function manage(User $user): bool
    {
        return ! $user->isDataCollector();
    }

    /**
     * A user may view/edit/delete a section only if they own that ward,
     * or are a SuperAdmin.
     */
    public function modifySection(User $user, SurveySection $section): bool
    {
        if ($user->isDataCollector()) {
            return false;
        }

        return $user->isSuperAdmin() || $section->ward_id == $user->ward_id;
    }
}
