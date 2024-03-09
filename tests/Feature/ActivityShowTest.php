<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use Tests\TestCase;
use App\Models\Activity;

class ActivityShowTest extends TestCase
{
    public function test_can_view_activity_page()
    {
        $activity = Activity::factory()->create(['start_time' => Carbon::now()->addDays(6)]);

        $response = $this->get(route('activity.show', $activity));

        $response->assertOk();
    }

    public function test_gets_404_for_unexisting_activity()
    {
        $response = $this->get(route('activity.show', 690));

        $response->assertNotFound();
    }
}
