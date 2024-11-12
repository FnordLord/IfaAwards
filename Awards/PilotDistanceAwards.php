<?php

namespace Modules\IfaAwards\Awards;

use App\Contracts\Award;
use Illuminate\Support\Facades\Log;

class PilotDistanceAwards extends Award
{
    public $name = 'Pilot Flight Distance';
    public $param_description = 'Amount of nautical miles flown at which to give this award';

    /**
     * Check if the pilot qualifies for this award based on the threshold.
     *
     * @param mixed $parameter The threshold parameter (ref_model_params) passed from the award configuration
     * @return bool
     */
    public function check($parameter = null): bool
    {
        Log::debug('PilotDistanceAwards: Starting check with ref_model_params', [
            'ref_model_params' => $parameter,
        ]);

        // Validate and cast ref_model_params as integer
        if (!is_numeric($parameter)) {
            Log::error('PilotDistanceAwards: ref_model_params is not a valid numeric value', [
                'ref_model_params' => $parameter,
            ]);
            return false;
        }

        $threshold = (int) $parameter;
        Log::debug('PilotDistanceAwards: Successfully cast ref_model_params to threshold', [
            'threshold' => $threshold,
        ]);

        // Retrieve and sum the total distance flown by the user from all completed PIREPs, depending on whether the planned or actually flown distance is greater; problem here is that ACARS does not always reports the correct distance, which makes it difficult to calc the real flown distance
        $pireps = $this->user->pireps()->where('state', '2'); // '2' state means completed
        $planned_distance = $pireps->sum('planned_distance');
        $flown_distance = $pireps->sum('distance');
        if ($flown_distance > $planned_distance) {
            $totalDistance = $flown_distance;
        } else {
            $totalDistance = $planned_distance;
        }

        Log::debug('PilotDistanceAwards: Retrieved PIREPs for user', [
            'pirep_count' => $pireps->count(),
            'total_distance' => $totalDistance,
            'threshold' => $threshold,
        ]);

        return $totalDistance >= $threshold;
    }
}
