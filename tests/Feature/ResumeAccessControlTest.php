<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Resume;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeAccessControlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can view their own resume.
     *
     * @return void
     */
    public function test_user_can_view_their_own_resume()
    {
        // Create a user and associate a resume with them
        $user = User::factory()->create();
        $resume = Resume::factory()->create(['user_id' => $user->id]);

        // Act as the user and make a GET request to the route that shows their resume
        $response = $this->actingAs($user)->get(route('resumes.view'));

        // Assert the response status is 200, meaning the resume is accessible
        $response->assertStatus(200);
    }

    /**
     * Test that a user can access the edit page of their own resume.
     *
     * @return void
     */
    public function test_user_can_access_edit_page_of_their_own_resume()
    {
        // Create a user and associate a resume with them
        $user = User::factory()->create();
        $resume = Resume::factory()->create(['user_id' => $user->id]);

        // Act as the user and make a GET request to the route that shows the edit form for their resume
        $response = $this->actingAs($user)->get(route('resumes.edit'));

        // Assert the response status is 200, meaning the edit page is accessible
        $response->assertStatus(200);
    }


}
