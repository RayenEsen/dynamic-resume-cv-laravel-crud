<?php

namespace App\Http\Controllers;

use App\Models\{Resume, PersonalInformation, Skills, Experience, Projects, ContactInformation, Education, Interests, Languages};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResumeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('resumes.create');
    }

    public function store(Request $request)
    {

            // Check if the user already has a resume
    if (auth()->user()->resume) {
        return response()->json([
            'message' => 'You already have a resume. You can edit it instead.',
            'resume_id' => auth()->user()->resume->resume_id,
        ], 409); // 409 Conflict status code
    }

        $validatedData = $request->validate([
            'personal_information.first_name' => 'required|string|max:255',
            'personal_information.last_name' => 'required|string|max:255',
            'personal_information.profile_title' => 'required|string|max:255',
            'personal_information.about_me' => 'nullable|string|max:1000',
            'contact_information.email' => 'required|email|max:255',
            'contact_information.phone' => 'nullable|string|max:255',
            'contact_information.website' => 'nullable|url|max:255',
            'contact_information.linkedin' => 'nullable|url|max:255',
            'contact_information.github' => 'nullable|url|max:255',
            'contact_information.twitter' => 'nullable|string|max:255',
            'degree_title.*' => 'required|string|max:255',
            'institute.*' => 'required|string|max:255',
            'edu_start_date.*' => 'required|date',
            'edu_end_date.*' => 'nullable|date|after_or_equal:edu_start_date.*',
            'education_description.*' => 'nullable|string',
            'job_title.*' => 'required|string|max:255',
            'organization.*' => 'required|string|max:255',
            'job_start_date.*' => 'required|date',
            'job_end_date.*' => 'nullable|date|after_or_equal:job_start_date.*',
            'job_description.*' => 'nullable|string',
            'project_title.*' => 'nullable|string|max:255',
            'project_description.*' => 'nullable|string|max:1000',
            'skill_name.*' => 'nullable|string|max:255',
            'skill_percentage.*' => 'nullable|integer|min:0|max:100',
            'interest.*' => 'nullable|string|max:255',
            'language.*' => 'nullable|string|max:255',
            'language_level.*' => 'nullable|string|in:Native,Fluent,Basic',
        ]);

        // Create the resume
        $resume = Resume::create([
            'user_id' => auth()->user()->id,
        ]);

        // Store personal info
        PersonalInformation::create([
            'resume_id' => $resume->resume_id,
            'first_name' => $validatedData['personal_information']['first_name'],
            'last_name' => $validatedData['personal_information']['last_name'],
            'profile_title' => $validatedData['personal_information']['profile_title'],
            'about_me' => $validatedData['personal_information']['about_me'],
        ]);

        // Store contact info
        $this->storeContactInformation($validatedData['contact_information'], $resume->resume_id);
        
        // Store education info
        $this->storeEducation($validatedData, $resume->resume_id);

        // Store experience info
        $this->storeExperience($validatedData, $resume->resume_id);

        // Store project info
        $this->storeProjects($validatedData, $resume->resume_id);

        // Store skills
        $this->storeSkills($validatedData, $resume->resume_id);

        // Store interests
        $this->storeInterests($validatedData, $resume->resume_id);

        // Store languages
        $this->storeLanguages($validatedData, $resume->resume_id);

    // Redirect to the view resume page
    return redirect()->route('resumes.view')
        ->with('success', 'Resume created successfully!');
        
    }

    private function storeContactInformation($contactData, $resumeId)
    {
        ContactInformation::create([
            'resume_id' => $resumeId,
            'email' => $contactData['email'] ?? null,
            'phone_number' => $contactData['phone'] ?? null,
            'website' => $contactData['website'] ?? null,
            'linkedin_link' => $contactData['linkedin'] ?? null,
            'github_link' => $contactData['github'] ?? null,
            'twitter' => $contactData['twitter'] ?? null,
        ]);
    }

    private function storeEducation($data, $resumeId)
    {
        foreach ($data['degree_title'] as $index => $degreeTitle) {
            Education::create([
                'resume_id' => $resumeId,
                'degree_title' => $degreeTitle,
                'institute' => $data['institute'][$index],
                'edu_start_date' => $data['edu_start_date'][$index],
                'edu_end_date' => $data['edu_end_date'][$index] ?? null,
                'education_description' => $data['education_description'][$index] ?? null,
            ]);
        }
    }

    private function storeExperience($data, $resumeId)
    {
        foreach ($data['job_title'] as $index => $jobTitle) {
            Experience::create([
                'resume_id' => $resumeId,
                'job_title' => $jobTitle,
                'organization' => $data['organization'][$index],
                'job_start_date' => $data['job_start_date'][$index],
                'job_end_date' => $data['job_end_date'][$index] ?? null,
                'job_description' => $data['job_description'][$index] ?? null,
            ]);
        }
    }

    private function storeProjects($data, $resumeId)
    {
        foreach ($data['project_title'] as $index => $projectTitle) {
            Projects::create([
                'resume_id' => $resumeId,
                'project_title' => $projectTitle,
                'project_description' => $data['project_description'][$index] ?? null,
            ]);
        }
    }

    private function storeSkills($data, $resumeId)
    {
        foreach ($data['skill_name'] as $index => $skillName) {
            Skills::create([
                'resume_id' => $resumeId,
                'skill_name' => $skillName,
                'skill_percentage' => $data['skill_percentage'][$index] ?? null,
            ]);
        }
    }

    private function storeInterests($data, $resumeId)
    {
        foreach ($data['interest'] as $interest) {
            Interests::create([
                'resume_id' => $resumeId,
                'interest' => $interest,
            ]);
        }
    }

    private function storeLanguages($data, $resumeId)
    {
        foreach ($data['language'] as $index => $language) {
            Languages::create([
                'resume_id' => $resumeId,
                'language' => $language,
                'language_level' => $data['language_level'][$index] ?? null,
            ]);
        }
    }

    public function show()
    {
        // Fetch the authenticated user's resume with relationships
        $resume = Resume::with([
            'personalInformation',   // personal information relation
            'education',             // education relation
            'experience',            // experience relation
            'skills',                // skills relation
            'projects',              // projects relation
            'interests',             // interests relation
            'languages',             // languages relation
            'contactInformation'     // contact information relation
        ])
        ->where('user_id', auth()->user()->id)
        ->first(); // Use `first()` instead of `get()` to fetch a single resume
    
        // Check if the resume exists
        if (!$resume) {
            return redirect()->route('resume.create')->with('error', 'No resume found. Please create a resume.');
        }
    
        // Transform the resume data into the $information array format
        $information = [
            'personal_info' => $resume->personalInformation,
            'contact_info' => $resume->contactInformation,
            'education_info' => $resume->education,
            'experience_info' => $resume->experience,
            'project_info' => $resume->projects,
            'skill_info' => $resume->skills,
            'language_info' => $resume->languages,
            'interest_info' => $resume->interests,
        ];
    
        // Log the fetched data
        Log::info('Fetched Resume with relationships:', $information); // Log as an array
    
        // Return the view with the transformed data
        return view('resumes.view', compact('information'));
    }

    public function edit()
    {
        // Fetch the authenticated user's resume with relationships
        $resume = Resume::with([
            'personalInformation',   // personal information relation
            'education',             // education relation
            'experience',            // experience relation
            'skills',                // skills relation
            'projects',              // projects relation
            'interests',             // interests relation
            'languages',             // languages relation
            'contactInformation'     // contact information relation 
        ])
        ->where('user_id', auth()->user()->id)
        ->first(); // Use `first()` instead of `get()` to fetch a single resume
    
// Log the fetched resume with relationships
if ($resume) {
    // Convert the resume object to an array including its relationships
    $resumeArray = $resume->toArray();

    // Log the array
    Log::info('Fetched Resume with relationships for editing:', $resumeArray);
} else {
    Log::warning('No resume found for the authenticated user.');
}

    
        // Transform the resume data into the $information array format
        $information = [
            'personal_info' => $resume->personalInformation,
            'contact_info' => $resume->contactInformation,
            'education_info' => $resume->education,
            'experience_info' => $resume->experience,
            'project_info' => $resume->projects,
            'skill_info' => $resume->skills,
            'language_info' => $resume->languages,
            'interest_info' => $resume->interests,
        ];
    
        // Log the fetched data
        Log::info('Fetched Resume with relationships for editing:', $information); // Log as an array
    
        // Pass the transformed data to the edit view
        return view('resumes.edit', compact('information'));
    }
    public function update(Request $request)
    {
        // Fetch the authenticated user's resume
        $resume = auth()->user()->resume;

        // If the user does not have a resume, redirect them to create one
        if (!$resume) {
            return redirect()->route('resumes.create')
                ->with('error', 'You do not have a resume. Please create one first.');
        }

        // Validate the form data
        $validatedData = $request->validate([
            // Personal Information
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'profile_title' => 'nullable|string|max:255',
            'about_me' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',

            // Contact Information
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'github_link' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:255',

            // Education (arrays for multiple entries)
            'degree_title.*' => 'nullable|string|max:255',
            'institute.*' => 'nullable|string|max:255',
            'edu_start_date.*' => 'nullable|date',
            'edu_end_date.*' => 'nullable|date|after_or_equal:edu_start_date.*',
            'education_description.*' => 'nullable|string',

            // Experience (arrays for multiple entries)
            'job_title.*' => 'nullable|string|max:255',
            'organization.*' => 'nullable|string|max:255',
            'job_start_date.*' => 'nullable|date',
            'job_end_date.*' => 'nullable|date|after_or_equal:job_start_date.*',
            'job_description.*' => 'nullable|string',

            // Projects (arrays for multiple entries)
            'project_title.*' => 'nullable|string|max:255',
            'project_description.*' => 'nullable|string|max:1000',

            // Skills (arrays for multiple entries)
            'skill_name.*' => 'nullable|string|max:255',
            'skill_percentage.*' => 'nullable|integer|min:0|max:100',

            // Interests (arrays for multiple entries)
            'interest.*' => 'nullable|string|max:255',

            // Languages (arrays for multiple entries)
            'language.*' => 'nullable|string|max:255',
            'language_level.*' => 'nullable|string|in:Native,Fluent,Basic',
        ]);

        // Update Personal Information
        $resume->personalInformation->update([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'profile_title' => $validatedData['profile_title'],
            'about_me' => $validatedData['about_me'],
        ]);

        // Handle file upload (if applicable)
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('resumes', 'public');
            $resume->personalInformation->update(['image_path' => $imagePath]);
        }

        // Update Contact Information
        $resume->contactInformation->update([
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'website' => $validatedData['website'],
            'linkedin_link' => $validatedData['linkedin_link'],
            'github_link' => $validatedData['github_link'],
            'twitter' => $validatedData['twitter'],
        ]);

        // Update Education
        $this->updateEducation($validatedData, $resume->resume_id);

        // Update Experience
        $this->updateExperience($validatedData, $resume->resume_id);

        // Update Projects
        $this->updateProjects($validatedData, $resume->resume_id);

        // Update Skills
        $this->updateSkills($validatedData, $resume->resume_id);

        // Update Interests
        $this->updateInterests($validatedData, $resume->resume_id);

        // Update Languages
        $this->updateLanguages($validatedData, $resume->resume_id);

        // Redirect to the view resumes page with a success message
        return redirect()->route('resumes.view')
            ->with('success', 'Resume updated successfully!');
    }

    // Helper Methods

    private function updateEducation($data, $resumeId)
    {
        // Delete existing education entries
        Education::where('resume_id', $resumeId)->delete();

        // Add new education entries
        if (isset($data['degree_title'])) {
            foreach ($data['degree_title'] as $index => $degreeTitle) {
                Education::create([
                    'resume_id' => $resumeId,
                    'degree_title' => $degreeTitle,
                    'institute' => $data['institute'][$index] ?? null,
                    'edu_start_date' => $data['edu_start_date'][$index] ?? null,
                    'edu_end_date' => $data['edu_end_date'][$index] ?? null,
                    'education_description' => $data['education_description'][$index] ?? null,
                ]);
            }
        }
    }

    private function updateExperience($data, $resumeId)
    {
        // Delete existing experience entries
        Experience::where('resume_id', $resumeId)->delete();

        // Add new experience entries
        if (isset($data['job_title'])) {
            foreach ($data['job_title'] as $index => $jobTitle) {
                Experience::create([
                    'resume_id' => $resumeId,
                    'job_title' => $jobTitle,
                    'organization' => $data['organization'][$index] ?? null,
                    'job_start_date' => $data['job_start_date'][$index] ?? null,
                    'job_end_date' => $data['job_end_date'][$index] ?? null,
                    'job_description' => $data['job_description'][$index] ?? null,
                ]);
            }
        }
    }

    private function updateProjects($data, $resumeId)
    {
        // Delete existing project entries
        Projects::where('resume_id', $resumeId)->delete();

        // Add new project entries
        if (isset($data['project_title'])) {
            foreach ($data['project_title'] as $index => $projectTitle) {
                Projects::create([
                    'resume_id' => $resumeId,
                    'project_title' => $projectTitle,
                    'project_description' => $data['project_description'][$index] ?? null,
                ]);
            }
        }
    }

    private function updateSkills($data, $resumeId)
    {
        // Delete existing skill entries
        Skills::where('resume_id', $resumeId)->delete();

        // Add new skill entries
        if (isset($data['skill_name'])) {
            foreach ($data['skill_name'] as $index => $skillName) {
                Skills::create([
                    'resume_id' => $resumeId,
                    'skill_name' => $skillName,
                    'skill_percentage' => $data['skill_percentage'][$index] ?? null,
                ]);
            }
        }
    }

    private function updateInterests($data, $resumeId)
    {
        // Delete existing interest entries
        Interests::where('resume_id', $resumeId)->delete();

        // Add new interest entries
        if (isset($data['interest'])) {
            foreach ($data['interest'] as $interest) {
                Interests::create([
                    'resume_id' => $resumeId,
                    'interest' => $interest,
                ]);
            }
        }
    }

    private function updateLanguages($data, $resumeId)
    {
        // Delete existing language entries
        Languages::where('resume_id', $resumeId)->delete();

        // Add new language entries
        if (isset($data['language'])) {
            foreach ($data['language'] as $index => $language) {
                Languages::create([
                    'resume_id' => $resumeId,
                    'language' => $language,
                    'language_level' => $data['language_level'][$index] ?? null,
                ]);
            }
        }
    }
}
