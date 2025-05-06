<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserData;
use App\Models\Employee;
use App\Mail\EmployeeProjectAwaitingApprovalMail;
use App\Mail\HRProjectApprovalRequestMail;
use Illuminate\Support\Facades\Mail;


use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Upload Cover Photo
     */
    public function uploadCoverPhoto(Request $request)
    {
        // Validate the request
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'cover_photo' => 'required|image|max:2048',
        ]);
    
        try {
            $employeeId = $request->employee_id;
            $employee = Employee::findOrFail($employeeId);
    
            if ($request->hasFile('cover_photo')) {
                if ($employee->getFirstMedia('cover_photos')) {
                    $employee->clearMediaCollection('cover_photos');
                }
    
                $employee->addMedia($request->file('cover_photo'))
                    ->toMediaCollection('cover_photos');
            }
    
            return redirect()->route('profiledetails')->with('success', 'Cover photo updated successfully.');
        } catch (\Exception $e) {
            Log::error('Cover photo upload failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload cover photo.');
        }
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Ensure employee exists
            'project_name' => 'required|string|max:255',
            'project_description' => 'nullable|string',
            'skills' => 'nullable|array',
            'contributors' => 'nullable|array',
            'media' => 'nullable|array',
        ]);

        Log::info('Validated Data:', $validated); // Log validated data

        // Save the project
        $userData = UserData::create([
            'employee_id' => $validated['employee_id'],
            'project_name' => $validated['project_name'],
            'project_description' => $validated['project_description'] ?? null,
            'skills' => json_encode($validated['skills']),
            'contributors' => json_encode($validated['contributors']),
            'media' => json_encode($validated['media']),

        ]);
        // Handle media uploads
        $mediaPaths = [];
        // dd($request->all());
        // dd($request->hasFile('media'));
        // if ($request->hasFile('media')) {
        //     Log::info('Uploaded Media Files:', $request->file('media'));

        //     foreach ($request->file('media') as $mediaFile) {
        //         $path = $mediaFile->store('project_media', 'public');
        //         Log::info('Media File Stored At:', ['path' => $path]);
        //         $mediaPaths[] = $path;
        //     }
        // }
        // dd($request->all());
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $userData->addMedia($file)->toMediaCollection('project_media');
            }
        }



        // Fetch the email addresses
        $employee = Employee::find($validated['employee_id']);
        $employeeEmail = $employee->email;
        $employeeName = $employee->name;
        $hrEmail = 'hr@weblook.com'; // Replace with HR's email

        // Send email to the employee
        Mail::to($employeeEmail)->send(
            new EmployeeProjectAwaitingApprovalMail($employeeName, $userData->project_name)
        );

        // Send email to HR
        $emailData = [
            'project_name' => $userData->project_name,
            'project_description' => $userData->project_description,
            'skills' => $userData->skills,
            'contributors' => $userData->contributors,
        ];
        Mail::to($hrEmail)->send(
            new HRProjectApprovalRequestMail($employeeName, $emailData)
        );

        return redirect()->route('profiledetails');
    }


    public function getProjects($employeeId)
    {
        try {
            Log::info('Fetching projects for employee ID:', ['employee_id' => $employeeId]);

            $projects = UserData::where('employee_id', $employeeId)->with('media')->get();

            Log::info('Projects retrieved:', $projects->toArray());

            return response()->json($projects);
        } catch (\Exception $e) {
            Log::error('Error fetching projects:', ['error' => $e->getMessage()]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update an existing project
    public function update(Request $request, $project_id)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'project_description' => 'nullable|string',
            'skills' => 'nullable|string', // JSON string
        ]);

        $project = UserData::findOrFail($project_id);

        $project->update([
            'project_name' => $request->project_name,
            'project_description' => $request->project_description,
            'skills' => $request->skills,
        ]);

        return response()->json($project, 200);
    }
    // Delete a project
    public function deleteProject($id)
    {
        try {
            $project = UserData::findOrFail($id);
            $project->delete();
            return response()->json(['message' => 'Project deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete project'], 500);
        }
    }



    /**
     * Get Employee Profile
     */
    public function getEmployeeProfile($employee_id)
    {
        $employee = Employee::with('userData')->find($employee_id);

        if (!$employee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }

        return response()->json([
            'employee' => $employee,
            'cover_photo_url' => $employee->userData->cover_photo
                ? asset('storage/' . $employee->userData->cover_photo)
                : null,
        ]);
    }
}
