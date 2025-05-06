<?php

namespace App\Http\Controllers;

use App\Models\JobRole;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobRoles = JobRole::all();
        return Inertia::render('JobRole/Index', ['jobRoles' => $jobRoles]);
    }


    /**
     * Fetch data for DataTables.
     */
    public function getJobRolesData()
    {
        $jobRoles = JobRole::all();

        return DataTables::of($jobRoles)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
                    <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
                    <label class="form-check-label" for="' . $row->id . '"></label>
                </div>';
            })
            ->addColumn('name', function ($row) {
                return $row->name; // Assuming 'name' exists in your database
            })
            ->addColumn('leave_types', function ($row) {
                $leaveTypes = json_decode($row->leave_types, true);
                if (is_array($leaveTypes)) {
                    return implode('<br>', array_map(
                        fn($leave) => "<span class='badge bg-primary'>{$leave['type']}: {$leave['allowed_leaves']} Days</span>",
                        $leaveTypes
                    ));
                }
                return '<span class="badge bg-secondary">No Leave Types</span>';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1 && !$row->deleted_at) {
                    return '<span class="badge bg-success">Active</span>';
                } else if ($row->status == 0 && !$row->deleted_at) {
                    return '<span class="badge bg-warning">Inactive</span>';
                } else if ($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $action_html = '';

                // Check if user has the required permissions
                if (auth()->user()->can('job-roles.view')) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }

                if (auth()->user()->can('job-roles.edit')) {
                    $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? 'Deactivate' : 'Activate') . '</a>';
                }

                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('job-roles.delete')) {
                    $action_html .= '<a class="dropdown-item text-danger action_delete" data-bs-toggle="modal" data-bs-target="#deleteConfirm" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-trash mr-2"></i> Delete</a> ';
                }

                return '<div class="btn-group">
                <button type="button" class="btn btn-main btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Action
                </button>
                <div class="dropdown-menu" style="min-width: 10rem;">
                ' . $action_html . '
                </div>
            </div>';
            })


            ->rawColumns(['check', 'leave_types', 'status', 'action'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('JobRole/CreateUpdate');
    }

    /**
     * Store a newly created resource in storage.
     */
    //     public function store(Request $request)
    // {
    //     Log::info('Request Data:', $request->all()); // Log all input to debug

    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'leave_types' => 'nullable|array',
    //         'status' => 'required|boolean',
    //     ]);

    //     Log::info('Validated Data:', $validated); // Check validated data

    //     $validated['leave_types'] = json_encode($request->leave_types ?? []);

    //     JobRole::create($validated);

    //     return redirect()->route('job-roles.index')->with('success', 'Job Role created successfully.');
    // }
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leave_types' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $jobRole = new JobRole();
            $jobRole->name = $request->name;
            $jobRole->status = $request->status;
            $jobRole->leave_types = json_encode($request->leave_types);

            $jobRole->save();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return abort(500);
        }


        return redirect()->route('job-roles.index')->with('success', 'Job Role created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobRole $jobRole)
    {
        return Inertia::render('JobRole/CreateUpdate', [
            'jobRole' => $jobRole
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, JobRole $jobRole)
    {
        //   dd($request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'leave_types' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $jobRole = JobRole::find($request->id);
            $jobRole->name = $request->name;
            $jobRole->status = $request->status;
            $jobRole->leave_types = json_encode($request->leave_types);

            $jobRole->save();

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return abort(500);
        }

        return redirect()->route('job-roles.index')->with('success', 'Job Role updated successfully.');
    }
    public function updateStatus(Request $request)
    {
        try {
            // Fetch Job Role
            $user = JobRole::find($request->id);

            // Check if job role exists
            if (!$user) {
                return response()->json(['error' => 'Job Role not found.'], 404);
            }

            // Toggle Status
            $user->status = $request->status == 0 ? 1 : 0;

            // Save Updated Status
            $user->save();

            return redirect()->route('job-roles.index')->with('success', 'Status updated successfully.');
        } catch (\Exception $ex) {
            Log::error($ex);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            JobRole::destroy([$request->ids]);
            return redirect()->route('job-roles.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
