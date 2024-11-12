<?php

namespace Modules\IfaAwards\Listeners;

use App\Events\PirepAccepted;
use App\Models\Award;
use Illuminate\Support\Facades\Log;
use Modules\IfaAwards\Awards\PilotDistanceAwards;

class CheckPilotDistanceAwards
{
    public function handle(PirepAccepted $event)
    {
        $pilot = $event->pirep->user;

        // Calculate total cumulative distance on-the-fly from all completed PIREPs
        $planned_distance = $pilot->pireps()->sum('planned_distance');
        $flown_distance = $pilot->pireps()->sum('distance');
        if ($flown_distance > $planned_distance) {
            $totalDistance = $flown_distance;
        } else {
            $totalDistance = $planned_distance;
        }

        Log::debug('CheckPilotDistanceAwards: Calculated total cumulative distance', [
            'pilot_id' => $pilot->id,
            'total_distance' => $totalDistance,
        ]);

        // Retrieve all distance-based awards
        $distanceAwards = Award::where('type', 'PilotDistanceAwards')->get();

        foreach ($distanceAwards as $distanceAward) {
            $awardChecker = new PilotDistanceAwards;
            $awardChecker->user = $pilot;

            // Check if the pilot qualifies based on the calculated distance
            $awardEligible = $awardChecker->check($distanceAward->ref_model_params);

            Log::debug('CheckPilotDistanceAwards: Award eligibility check', [
                'pilot_id' => $pilot->id,
                'award_id' => $distanceAward->id,
                'award_eligible' => $awardEligible,
                'total_distance' => $totalDistance,
                'threshold' => $awardChecker->threshold,
            ]);

            if ($awardEligible) {
                Log::info('CheckPilotDistanceAwards: Award issued', [
                    'pilot_id' => $pilot->id,
                    'award_id' => $distanceAward->id,
                    'total_distance' => $totalDistance,
                ]);

                // Sync the award if the pilot doesn’t already have it
                $pilot->awards()->syncWithoutDetaching([$distanceAward->id]);
            }
        }
    }
}
