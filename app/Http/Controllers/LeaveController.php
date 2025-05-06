<?php

namespace App\Http\Controllers;

use App\Models\EmpLeave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Employee;
use Exception;
use App\Mail\LeaveApplicationMail;
use App\Mail\LeaveApplicationSuccess;
use App\Mail\LeaveStatusUpdatedToHR;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{

    public function index(Request $request)
    {
        // Retrieve the currently logged-in user
        $user = Auth::guard('employee')->user();

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['message' => 'You must be logged in to apply for leave.']);
        }

        // Ensure leave_types is decoded only if it's not already an array
        $leaveTypes = is_array($user->leave_types) ? $user->leave_types : json_decode($user->leave_types, true);

        return Inertia::render('Home/LeaveApplyForm', [
            'employee_id' => $user->id,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'designation' => $user->designation,
                'leave_types' => $leaveTypes, // Pass decoded or array directly
            ],
        ]);
    }

    // Store Leave Application
    public function store(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'partial_days' => 'nullable|string',
            'duration' => 'nullable|string',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'comments' => 'nullable|string',
           //'proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total leave days
            $fromDate = new \DateTime($validatedData['from_date']);
            $toDate = new \DateTime($validatedData['to_date']);
            $totalDays = $toDate->diff($fromDate)->days + 1; // Include both start and end dates

            $leaveDuration = $totalDays; // Initialize leave duration with total days

            // Adjust for partial days
            if (!empty($validatedData['partial_days'])) {
                switch ($validatedData['partial_days']) {
                    case 'Start Day Only':
                    case 'End Day Only':
                        $leaveDuration -= 0.5; // Deduct 0.5 for start or end day
                        break;
                    case 'Start and End Day':
                        $leaveDuration -= 1; // Deduct 1 full day
                        break;
                    default:
                        break;
                }
            }

            // Adjust for specific duration
            if (!empty($validatedData['duration'])) {
                switch ($validatedData['duration']) {
                    case 'Half Day - Morning':
                    case 'Half Day - Afternoon':
                        $leaveDuration *= 0.5; // Each day is counted as 0.5 days
                        break;
                    case 'Specify Time':
                        // Implement additional logic for specific time if needed
                        $leaveDuration = 0.5; // Assume half day for specific time
                        break;
                    default:
                        break;
                }
            }

            $leaveDuration = max(0, $leaveDuration);

            // Fetch employee record
            $employee = Employee::findOrFail($validatedData['employee_id']);

            // Decode leave_types JSON
            $leaveTypes = $employee->leave_types;

            // Find the selected leave type
            $leaveTypeIndex = array_search($validatedData['leave_type'], array_column($leaveTypes, 'type'));
            if ($leaveTypeIndex === false) {
                throw new \Exception('Invalid leave type selected.');
            }

            // Check if enough available leaves
            $selectedLeaveType = &$leaveTypes[$leaveTypeIndex];
            if ($selectedLeaveType['available_leaves'] < $leaveDuration) {
                return back()->withErrors(['message' => 'Insufficient available leaves for this leave type.']);
            }

            // Update used_leaves and available_leaves
            $selectedLeaveType['used_leaves'] += $leaveDuration;
            $selectedLeaveType['available_leaves'] = max(0, $selectedLeaveType['user_allowed_leaves'] - $selectedLeaveType['used_leaves']);

            // Save the updated leave_types back to the employee record
            $employee->leave_types = $leaveTypes;
            $employee->save();

            // Store the leave application
            $leave = new EmpLeave();
            $leave->employee_id = $validatedData['employee_id'];
            $leave->leave_type = $validatedData['leave_type'];
            $leave->from_date = $validatedData['from_date'];
            $leave->to_date = $validatedData['to_date'];
            $leave->partial_days = $validatedData['partial_days'] ?? null;
            $leave->duration = $leaveDuration . ' days';
            $leave->start_time = $validatedData['start_time'] ?? null;
            $leave->end_time = $validatedData['end_time'] ?? null;
            $leave->comments = $validatedData['comments'] ?? null;
            // Inside the `store` method
            if ($leave->save()) {
                // Prepare the leave details for the email
                $leaveDetails = [
                    'employee_name' => $employee->name,
                    'leave_type' => $validatedData['leave_type'],
                    'from_date' => $validatedData['from_date'],
                    'to_date' => $validatedData['to_date'],
                    'duration' => $leaveDuration . ' days',
                    'comments' => $validatedData['comments'] ?? null,
                    'proof' => $request->hasFile('proof'),
                ];


                $hrEmail = 'hr@weblook.com';
                Mail::to($hrEmail)->send(new LeaveApplicationMail($leaveDetails));
                Mail::to($employee->email)->send(new LeaveApplicationSuccess($leaveDetails));
            }
            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
                Log::info($proofPath);
                $leave->proof = $proofPath;
            }

            $leave->save();

            DB::commit();

            return redirect()->route('index')->with('success', 'Leave application submitted successfully.');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            Log::error('Error while storing leave application: ' . $e->getMessage());
            return redirect()->back()->withErrors(['message' => 'An error occurred while submitting your leave application.']);
        }
    }



    public function getLeaveTypes($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json(['leave_types' => $employee->leave_types]);
    }


    // Employee: Edit Leave Form
    // public function editLeave($id)
    // {
    //     $leave = EmpLeave::where('employee_id', Auth::id())->findOrFail($id);

    //     return Inertia::render('Home/EditLeaveForm', [

    //         'leave' => $leave,
    //     ]);
    // }

    // Employee: Update Leave Request
    // public function updateLeave(Request $request, $id)
    // {
    //     $leave = EmpLeave::where('employee_id', Auth::id())->findOrFail($id);

    //     $request->validate([
    //         'leave_type' => 'required|string',
    //         'from_date' => 'required|date',
    //         'to_date' => 'required|date|after_or_equal:from_date',
    //         'partial_days' => 'required|string',
    //         'duration' => 'required|string',
    //         'start_time' => 'nullable|date_format:H:i',
    //         'end_time' => 'nullable|date_format:H:i|after:start_time',
    //         'comments' => 'nullable|string',
    //         'proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    //     ]);

    //     $leave->fill($request->only([
    //         'leave_type',
    //         'from_date',
    //         'to_date',
    //         'partial_days',
    //         'duration',
    //         'start_time',
    //         'end_time',
    //         'comments',
    //     ]));

    //     if ($request->hasFile('proof')) {
    //         $leave->proof = $request->file('proof')->store('proofs', 'public');
    //     }

    //     $leave->save();

    //     return redirect()->route('employee.leaves.index')->with('success', 'Leave updated successfully.');
    // }

    // Employee: Cancel Leave Request
    // public function cancel($id)
    // {
    //     $leave = EmpLeave::findOrFail($id);

    //     if ($leave->status !== 'Pending') {
    //         return response()->json(['error' => 'Only pending leaves can be canceled.'], 400);
    //     }

    //     $leave->delete();

    //     return;
    // }

    // Admin: View All Leaves
    public function adminIndex()
    {
        // Fetch leaves with related employee and job role data
        $leaves = EmpLeave::with(['employee.jobRole'])->get();

        // Format the data to include job role name (designation_name)
        $formattedLeaves = $leaves->map(function ($leave) {
            return [
                'id' => $leave->id,
                'employee' => [
                    'id' => $leave->employee->id,
                    'name' => $leave->employee->name,
                    'designation_name' => $leave->employee->jobRole->name ?? null, // Fetch job role name, handle null
                ],
                'leave_type' => $leave->leave_type,
                'proof' => $leave->proof,
                'status' => $leave->status,
                'from_date' => $leave->from_date,
                'to_date' => $leave->to_date,
                'partial_days' => $leave->partial_days,
                'duration' => $leave->duration,
                'start_time' => $leave->start_time,
                'end_time' => $leave->end_time,
                'comments' => $leave->comments,
                'instructions' => $leave->instructions,

            ];
        });

        // Return the data to the Inertia view
        return Inertia::render('Leaves/EmpLeaves', [
            'leaves' => $formattedLeaves,
        ]);
    }

    public function destroy($id)
    {
        try {
            $leave = EmpLeave::findOrFail($id);
            $leave->delete();

            return back()->with('success', 'Leave record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete leave record.');
        }
    }

    // Admin: Edit Leave Form
    // public function edit($id)
    // {
    //     $leave = EmpLeave::with('employee')->findOrFail($id);

    //     return Inertia::render('Admin/Leaves/Edit', [
    //         'leave' => $leave,
    //     ]);
    // }

    // Admin: Update Leave
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'leave_type' => 'required|string',
    //         'from_date' => 'required|date',
    //         'to_date' => 'required|date|after_or_equal:from_date',
    //         'partial_days' => 'required|string',
    //         'duration' => 'required|string',
    //         'start_time' => 'nullable|date_format:H:i',
    //         'end_time' => 'nullable|date_format:H:i|after:start_time',
    //         'comments' => 'nullable|string',
    //         'leave_status' => 'required|string',
    //     ]);

    //     $leave = EmpLeave::findOrFail($id);

    //     $leave->fill($request->only([
    //         'leave_type',
    //         'from_date',
    //         'to_date',
    //         'partial_days',
    //         'duration',
    //         'start_time',
    //         'end_time',
    //         'comments',
    //     ]));

    //     $leave->leave_status = $request->leave_status;

    //     $leave->save();

    //     return redirect()->route('admin.leaves.index')->with('message', 'Leave updated successfully.');
    // }

    // // Admin: Delete Leave
    // public function destroy($id)
    // {
    //     $leave = EmpLeave::findOrFail($id);
    //     $leave->delete();

    //     return redirect()->route('admin.leaves.index')->with('message', 'Leave deleted successfully.');
    // }

    // Admin: Update Leave Status

    public function updateStatus(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|exists:emp_leaves,id',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        // Fetch leave and update its status
        $leave = EmpLeave::findOrFail($request->leave_id);
        $leave->status = $request->status;
        $leave->instructions = $request->instructions;
        $leave->save();

        // Fetch employee details
        $employee = $leave->employee;

        // Prepare email data
        $mailData = [
            'employeeName' => $employee->name,
            'status' => $leave->status,
            'instructions' => $leave->instructions,
            'leaveType' => $leave->leave_type,
            'fromDate' => $leave->from_date,
            'toDate' => $leave->to_date,
        ];

        // Send email to employee
        Mail::send('emails.leave_status', $mailData, function ($message) use ($employee) {
            $message->to($employee->email)
                ->subject('Your Leave Application Status');
        });

        // Send mail to HR
        $hrEmail = 'hr@weblook.com';
        Mail::to($hrEmail)->send(new LeaveStatusUpdatedToHR($leave));

        return;
    }
}
