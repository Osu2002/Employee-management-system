<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\Bank_Details;
use App\Models\BankDetails;
use App\Models\Document;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Employee;
use App\Models\JobRole;
use Illuminate\Support\Str;
use App\Models\Leave;
use App\Models\Leaves;
use App\Models\Service;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Models\UserData;
use App\Mail\EmployeeProjectApprovedMail;

use PgSql\Lob;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter;
use Yajra\DataTables\Facades\DataTables;

class EmployeeController extends Controller
{
    //index function
    public function index()
    {

        return Inertia::render('Employee/Index');
    }

    //Create function
    public function create()
    {
        $jobRoles = JobRole::all()->map(function ($jobRole) {
            $jobRole->leave_types = json_decode($jobRole->leave_types, true);
            return $jobRole;
        });

        return Inertia::render('Employee/CreateUpdate', [
            'jobRoles' => $jobRoles
        ]);
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:employees,email',
            'designation' => 'required|exists:job_roles,id',
            'join_date' => 'required|date',
            'status' => 'required|in:1,0',
            'personal_email' => 'required|email|max:255',
            'nic' => 'required|max:15',
            'birthday' => 'required|date',
            'address' => 'required|max:255',
            'phone' => 'required|max:15',
            'home_telephone' => 'nullable|max:15',
            'password' => 'nullable|min:8|confirmed', // Password validation
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'leave_types' => 'required|array', // Ensure leave_types is provided as an array
            'leave_types.*.type' => 'required|string',
            'leave_types.*.default_allowed_leaves' => 'required|integer|min:0',
            'leave_types.*.user_allowed_leaves' => 'required|integer|min:0',
            'leave_types.*.used_leaves' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Find the job role
            $jobRole = JobRole::findOrFail($request->designation);

            // Process and update leave_types
            $processedLeaveTypes = collect($request->leave_types)->map(function ($leave) {
                $availableLeaves = $leave['user_allowed_leaves'] - $leave['used_leaves']; // Calculate available leaves

                return [
                    'type' => $leave['type'],
                    'default_allowed_leaves' => $leave['default_allowed_leaves'],
                    'user_allowed_leaves' => $leave['user_allowed_leaves'],
                    'used_leaves' => $leave['used_leaves'],
                    'available_leaves' => max($availableLeaves, 0), // Ensure non-negative value
                ];
            });

            // Create the employee
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->designation = $jobRole->id;
            $employee->join_date = $request->join_date;
            $employee->end_date = $request->end_date ?? null;
            $employee->status = $request->status;
            $employee->personal_email = $request->personal_email;
            $employee->nic = $request->nic;
            $employee->home_telephone = $request->home_telephone ?? null;
            $employee->birthday = $request->birthday;
            $employee->address = $request->address;
            $employee->phone = $request->phone;

            if ($request->filled('password')) {
                $employee->password = bcrypt($request->password); // Hash the password if provided
            }

            // Assign processed leave types
            $employee->leave_types = $processedLeaveTypes->toArray();

            $employee->save();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                $employee->addMedia($request->file('profile_image'))
                    ->toMediaCollection('employee_profile_image');
            }

            // Optional: Send welcome emails
            //Mail::to($employee->email)->send(new WelcomeMail($employee));
           // Mail::to($employee->personal_email)->send(new WelcomeMail($employee));

            DB::commit();

            return redirect()->route('employee')
                ->with('success', 'Employee created successfully!');
            dd($ex);
        } catch (\Exception $ex) {
            DB::rollBack();
            Log::error('Employee Store Error: ' . $ex->getMessage());
            dd($ex);

            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $ex->getMessage());
        }
    }





    //
    public function getdata(Request $request)
    {

        // dd($request);
        $users = Employee::all();

        return DataTables::of($users)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('id', function ($row) {
                return '<span class="">EMP' . ($row->id + 5000) . '</span>';
            })
            ->addColumn('action',  function ($row) {

                $action_html = '';
                if (auth()->user()->can('employee.view')) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('employee.edit')) {
                    $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('employee.create')) {
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
            })->addColumn('status', function ($row) {
                if ($row->status == 1 && !$row->deleted_at) {
                    return '<span class="badge bg-success">Active</span>';
                } else if ($row->status == 0 && !$row->deleted_at) {
                    return '<span class="badge bg-warning">Inactive</span>';
                } else if ($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })
            ->rawColumns(['id', 'check', 'action', 'status'])
            ->make(true);
    }

    //CreateUpdate edit function
    public function edit($id)
    {

        $encryptedId = base64_encode($id); // Encrypt the ID
        $jobRoles = JobRole::all()->map(function ($jobRole) {
            $jobRole->leave_types = json_decode($jobRole->leave_types, true);
            return $jobRole;
        });
        $employee = Employee::with('media')->find($id); // get employee data with relevant media (profile image)
        // $employee = Employee::with('media','bank_details')->find($id);  // this for when we want to pass bank details with employee and simply we can use it using object
        // $employee = Employee::find($id); // this can use all data pass with employee please check model line 18

        // dd($employee);
        // Generate a random string
        $randomString = Str::random(10);
        $employeeNameSlug = Str::slug($employee->name);
        $baseUrl = Config::get('app.url'); // Base URL from config
        $employeeUrl = $baseUrl . '/employee/qr/'. $employeeNameSlug . '/' . $encryptedId. '-' . $randomString; //Generate the URL with the encrypted ID

        // dd($employeeUrl);
        return Inertia::render('Employee/CreateUpdate', ['employee' => $employee, 'employeeUrl' => $employeeUrl, 'jobRoles' => $jobRoles]);

    }




    //CreateUpdate page data update function

    public function update(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'id' => 'required|exists:employees,id',
            'name' => 'required|max:255',
            'email' => 'required|email|unique:employees,email,' . $request->id,
            'designation' => 'required|exists:job_roles,id',
            'join_date' => 'required|date',
            'status' => 'required|in:1,0',
            'personal_email' => 'required|email|max:255',
            'nic' => 'required|max:15',
            'birthday' => 'required|date',
            'address' => 'required|max:255',
            'phone' => 'required|max:15',
            'home_telephone' => 'nullable|max:15',
            'password' => 'nullable|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'leave_types' => 'required|array', // Ensure leave_types is provided as an array
            'leave_types.*.type' => 'required|string', // Validate each leave type
            'leave_types.*.default_allowed_leaves' => 'required|integer|min:0',
            'leave_types.*.user_allowed_leaves' => 'required|numeric|min:0',
            'leave_types.*.used_leaves' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Find the employee
            $employee = Employee::findOrFail($request->id);

            $jobRole = JobRole::findOrFail($request->designation);

            // Update employee details
            $employee->name = $request->name;
            $employee->email = $request->email;
            $employee->designation = $jobRole->id;
            $employee->join_date = $request->join_date;
            $employee->end_date = $request->end_date ?? null;
            $employee->status = $request->status;
            $employee->personal_email = $request->personal_email;
            $employee->nic = $request->nic;
            $employee->home_telephone = $request->home_telephone ?? null;
            $employee->birthday = $request->birthday;
            $employee->address = $request->address;
            $employee->phone = $request->phone;

            if ($request->filled('password')) {
                $employee->password = bcrypt($request->password);
            }

            // Process and update leave_types
            $updatedLeaveTypes = collect($request->leave_types)->map(function ($leave) {
                $availableLeaves = $leave['user_allowed_leaves'] - $leave['used_leaves']; // Calculate available leaves

                return [
                    'type' => $leave['type'],
                    'default_allowed_leaves' => $leave['default_allowed_leaves'],
                    'user_allowed_leaves' => $leave['user_allowed_leaves'],
                    'used_leaves' => $leave['used_leaves'],
                    'available_leaves' => max($availableLeaves, 0), // Ensure non-negative value
                ];
            });

            $employee->leave_types = $updatedLeaveTypes->toArray();

            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                if ($employee->getMedia('employee_profile_image')->count() > 0) {
                    $employee->clearMediaCollection('employee_profile_image');
                }
                $employee->addMedia($request->file('profile_image'))
                    ->toMediaCollection('employee_profile_image');
            }

            $employee->save();

            DB::commit();

            return redirect()->route('employee')
                ->with('success', 'Employee updated successfully!');
        } catch (\Exception $ex) {
            //dd($ex);
            DB::rollBack();
            Log::error('Employee Update Error: ' . $ex->getMessage());

            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $ex->getMessage());
        }
    }


    //Delete function
    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Employee::destroy([$request->ids]);
            return redirect()->route('employee');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    //Update status function
    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = employee::find($request->id);

            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('employee');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    //Document function
    public function document($id)
    {
        $employee = Employee::with('document', 'media')->find($id); // using with('document') for get docment table data
        //  dd($employee);


        $document_data = Document::where(['employee_id' => $id])->with('media')->get();  // In this find line we can pass the data uing id .
        //  dd($document_data);

        return Inertia::render('Employee/Document', ['employee' => $employee, 'document_data' => $document_data]);
    }

    // Document store function
    public function document_store(Request $request)
    {
        //validate function
        //    dd($request->all());

        $request->validate([
            'upload_date' => 'required|max:255',
            'document_name' => 'required|max:255',
        ]);

        try {
            DB::beginTransaction();
            $document = new Document();
            $document->employee_id = $request->employee_id;
            $document->upload_date = $request->upload_date;
            $document->document_name = $request->document_name;
            $document->save();


            if ($request->hasFile('file')) {
                $document->addMedia($request->file('file'))->toMediaCollection('employee_document');
                $document->save();
            }

            DB::commit();
            return redirect()->route('employee.document', ['id' => $request->employee_id]); // When create user we have to move our page to index thats why we use redirect
        } catch (Exception $ex) {
            dd($ex);
        }




        return Redirect::route('employee.document')->with('message', 'Document upload succesfully'); //when update success, this message wil be display


    }

    public function document_distroy($employee_id, $id)
    {
        // dd($id);
        // Find the leave record by ID
        $document = Document::find($id);

        // If the record exists, delete it
        if ($document) {
            $document->delete();
            //  return response()->json(['message' => 'Leave record deleted successfully.'], 200);
            return redirect()->route('employee.document', $employee_id)->with('success', 'Leave record deleted successfully.');
        }

        // If the record does not exist, return an error response
        return response()->json(['message' => 'Leave record not found.'], 404);
    }

//Service Letter Display Function
    public function updateServiceLetterStatus($id, Request $request)
    {
       // dd($request->all());

        DB::beginTransaction();
        $document = Service::find($id);

        if ($document->service_letter_visible == 1) {
            $document->service_letter_visible = 0;
            $document->save();
        } elseif ($document->service_letter_visible == 0) {
            $document->service_letter_visible = 1;
            $document->save();
        }

        DB::commit();



        return Redirect::route('employee.document', $request->employee_id)->with('message', 'Display succesfully');
    }
    public function toggleVisibility(Request $request)
    {
        // Log the incoming request payload
        Log::info('Received toggleVisibility request', [
            'payload' => $request->all(),
        ]);

        // Validate the request
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);

        // Log the validated status
        Log::info('Validated status:', ['status' => $validated['status']]);

        try {
            // Assuming you have a model `Service` and the column `visibility` stores the status
            // Find the record that holds the visibility status (for example, the Service record)
            $service = Service::find(1); // Or the ID that you need to toggle

            if ($service) {
                // Toggle the visibility status (1 becomes 0, and 0 becomes 1)
                $newStatus = $service->service_letter_visible === 1 ? 0 : 1;

                // Update the visibility column in the database
                $service->update(['service_letter_visible' => $newStatus]);

                // Log the successful update
                Log::info('Visibility updated successfully', [
                    'newStatus' => $newStatus,
                ]);

                return response()->json([
                    'message' => 'Visibility updated successfully.',
                    'status' => $newStatus,  // Returning the new status in the response
                ], 200);
            } else {
                // If the Service record isn't found
                Log::error('Service not found', [
                    'serviceId' => 1, // Adjust this based on your application
                ]);

                return response()->json([
                    'message' => 'Service not found.',
                ], 404);
            }

        } catch (\Exception $e) {
            // Log the exception details
            Log::error('Error updating visibility:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Return an error response
            return response()->json([
                'message' => 'Failed to update visibility.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //document display funtion
    public function updateDisplayStatus($id, Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();
        $document = Document::find($id);

        if ($document->display_to_employee == 1) {
            $document->display_to_employee = 0;
            $document->save();
        } elseif ($document->display_to_employee == 0) {
            $document->display_to_employee = 1;
            $document->save();
        }

        DB::commit();



        return Redirect::route('employee.document', $request->employee_id)->with('message', 'Display succesfully');
    }

    //Filter documents
    public function showDocumentsForEmployees()
    {
        $documents = Document::where('display_to_employee', true)->get();
        return view('documents.employee-view', compact('documents'));
    }




    //Leaves function
    public function Leaves($id, Request $request)
    {
        $employee = Employee::with('leaves')->find($id); // using with('leaves') for get leaves table data
        // dd($employee);


        $leave_data = Leaves::with('media')->where(['employee_id' => $id])->get();  // In this find line we can pass the data uing id .
        //   dd($leave_data);

        return Inertia::render('Employee/LeavesIndex', ['employee' => $employee, 'leave_data' => $leave_data]);
    }
    public function leavegetdata($id)
    {
        // dd($id);
        $leave_data = Leaves::with('media')->where(['employee_id' => $id])->orderBy('id', 'desc')->get();

        return DataTables::of($leave_data)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('action',  function ($row) use ($id) {

                $action_html = '';
                if (auth()->user()->can('employee.leaves')) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-emp-id="' . $id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                // if (auth()->user()->can('employee.edit')) {
                //     $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                // }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('employee.leaves')) {
                    $action_html .= '<a class="dropdown-item text-danger action_delete" data-bs-toggle="modal" data-bs-target="#deleteConfirm" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-emp-id="' . $id . '" href="javascript:void(0)"><i class="fas fa-trash mr-2"></i> Delete</a> ';
                }
                return '<div class="btn-group">
                                <button type="button" class="btn btn-main btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" style="min-width: 10rem;">
                                ' . $action_html . '
                                </div>
                            </div>';
            })->addColumn('status', function ($row) {
                if ($row->leave_status == 'Approved') {
                    return '<span class="badge bg-success">Approved</span>';
                } else if ($row->leave_status == 'Pending') {
                    return '<span class="badge bg-warning">Pending</span>';
                } else if ($row->leave_status == 'Rejected') {
                    return '<span class="badge bg-danger">Rejected</span>';
                }
            })
            ->rawColumns(['check', 'action', 'status'])
            ->make(true);
    }
    public function leavescreate($id)
    {
        $employee = Employee::with('leaves')->find($id);

        return Inertia::render('Employee/Leaves',  ['employee' => $employee]);
    }
    public function leaves_edit($empid, $id)
    {
        // dd($empid);
        $employee = Employee::with('leaves')->find($empid);

        $leave_data = Leaves::with('media')->find($id);
        // dd($leave_data);
        return Inertia::render('Employee/Leaves',  ['employee' => $employee, 'leave_data' => $leave_data]);
    }
    //Leaves store funtion
    public function leaves_store(Request $request)
    {
        //validate function
        //    dd($request->all());

        $request->validate([
            'from_date' => 'required|max:255',
            'to' => 'required|max:255',
            'reason' => 'required|max:255',
            'type' => 'required|max:255',
            'leave_type' => 'required|max:255',
            'leave_status' => 'required|max:255',
            'employee_id' => 'required|integer'




        ]);
        // $employee = Employee::create($validated);
        try {
            DB::beginTransaction();
            $leaves = new Leaves();
            $leaves->employee_id = $request->employee_id;
            $leaves->from_date = $request->from_date;
            $leaves->to = $request->to;
            $leaves->reason = $request->reason;
            $leaves->type = $request->type;
            $leaves->leave_type = $request->leave_type;
            $leaves->leave_status = $request->leave_status;
            $leaves->save();

            if ($request->hasFile('proof_image')) {
                $leaves->addMedia($request->file('proof_image'))->toMediaCollection('leavesProof');
                $leaves->save();
            }


            DB::commit();
            return redirect()->route('employee.leaves.index', ['id' => $request->employee_id]); // When create user we have to move our page to index thats why we use redirect
        } catch (Exception $ex) {
            dd($ex);
        }
    }

    // leaves update function
    public function leaves_update(Request $request)
    {
        //validate function
        //    dd($request->all());

        $request->validate([
            'from_date' => 'required|max:255',
            'to' => 'required|max:255',
            'reason' => 'required|max:255',
            'type' => 'required|max:255',
            'leave_type' => 'required|max:255',
            'leave_status' => 'required|max:255',
            'employee_id' => 'required|integer'




        ]);
        // $employee = Employee::create($validated);
        try {
            DB::beginTransaction();
            $leaves = Leaves::find($request->leaeve_id);
            $leaves->employee_id = $request->employee_id;
            $leaves->from_date = $request->from_date;
            $leaves->to = $request->to;
            $leaves->reason = $request->reason;
            $leaves->type = $request->type;
            $leaves->leave_type = $request->leave_type;
            $leaves->leave_status = $request->leave_status;
            $leaves->save();

            if ($request->hasFile('proof_image')) {
                if ($leaves->media) {
                    Storage::disk('public')->delete($leaves->media);
                    $leaves->clearMediaCollection('leavesProof');
                }
                $leaves->addMedia($request->file('proof_image'))->toMediaCollection('leavesProof');
                $leaves->save();
            }


            DB::commit();
            return redirect()->route('employee.leaves.index', ['id' => $request->employee_id]); // When create user we have to move our page to index thats why we use redirect
        } catch (Exception $ex) {
            dd($ex);
        }
    }
    //delete funcion
    public function leaves_distroy(Request $request)
    {
        // dd($request->all());
        // // Find the leave record by ID
        try {
            Leaves::destroy([$request->ids]);
            return redirect()->route('employee.leaves.index', $request->employee_id);
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }


    public function certificate($id)
    {

        $encryptedId = base64_encode($id); // Encrypt the ID
        // $encryptedId = Crypt::encryptString($id);  // Encrypt the ID
        // dd($encryptedId);
        $employee = Employee::find($id); // using with('leaves') for get leaves table data
        $baseUrl = Config::get('app.url');
        $employeeUrl = $baseUrl . '/employee/qr/' . $encryptedId; //Generate the URL with the encrypted ID
        // $employeeUrl = $baseUrl . '/employee/' . 'EMP' . $id + 5000; // change id with EMP and 5000

        //    dd($employeeUrl);
        return Inertia::render('Employee/Certificate', ['employee' => $employee, 'employeeUrl' => $employeeUrl]);
    }

    public function bank_details($id)
    {

        $employee = Employee::find($id);


        $bank_data = BankDetails::where(['employee_id' => $id])->first();  // In this find line we can pass the data uing id .
        //   dd($bank_data);

        return Inertia::render('Employee/BankDetails', ['employee' => $employee, 'bank_data' => $bank_data]);
    }

    public function bank_details_store(Request $request)
    {
        //   dd($request->all());
        $request->validate([

            'employee_id' => 'required|integer',
            'account_holders_name' => 'required|max:255',
            'bank_name' => 'required|max:255',
            'bank_code' => 'required|max:255',
            'bank_account_number' => 'required|max:255',
            'branch_name' => 'required|max:255',
            'branch_code' => 'required',
            'linked_phone_number' => 'required'





        ]);
        // $employee = Employee::create($validated);
        try {
            DB::beginTransaction();
            $Bank = new BankDetails();
            $Bank->employee_id = $request->employee_id;
            $Bank->account_holders_name = $request->account_holders_name;
            $Bank->bank_name = $request->bank_name;
            $Bank->bank_code = $request->bank_code;
            $Bank->bank_account_number = $request->bank_account_number;
            $Bank->branch_name = $request->branch_name;
            $Bank->branch_code = $request->branch_code;
            $Bank->linked_phone_number = $request->linked_phone_number;


            $Bank->save();



            DB::commit();
            return redirect()->route('employee.edit', ['id' => $request->employee_id]); // When create user we have to move our page to index thats why we use redirect
        } catch (Exception $ex) {
            dd($ex);
        }

        return Redirect::route('employee.index')->with('message', 'Employee update succesfully'); //when update success, this message wil be display


    }

    //CreateUpdate edit function
    public function bank_details_edit($id)
    {
        $bank_data = BankDetails::find($id); // get  relevant data
        // dd( $bank_data);

        return Inertia::render('Employee/BankDetails', ['bank_data' =>  $bank_data]);
    }

    public function bank_details_update(Request $request)
    {

        $request->validate([
            'employee_id' => 'required|integer',
            'account_holders_name' => 'required|max:255',
            'bank_name' => 'required|max:255',
            'bank_code' => 'required|max:255',
            'bank_account_number' => 'required|max:255',
            'branch_name' => 'required|max:255',
            'branch_code' => 'required',
            'linked_phone_number' => 'required'




        ]);
        // dd($request);

        try {
            DB::beginTransaction();
            // Fetch the bank details using the bank_data ID
            $Bank = BankDetails::find($request->id);
            $Bank->account_holders_name = $request->account_holders_name;
            $Bank->bank_name = $request->bank_name;
            $Bank->bank_code = $request->bank_code;
            $Bank->bank_account_number = $request->bank_account_number;
            $Bank->branch_name = $request->branch_name;
            $Bank->branch_code = $request->branch_code;
            $Bank->linked_phone_number = $request->linked_phone_number;


            $Bank->save();

            DB::commit();


            return redirect()->route('employee', ['id' => $request->employee_id]);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function service_letter($id)
{
    $employee = Employee::find($id);
    $service_letter_data = Service::where(['employee_id' => $id])->first();

    // Generate the employee URL
    $encryptedId = base64_encode($id);
    $randomString = Str::random(10);
    $employeeNameSlug = Str::slug($employee->name);
    $baseUrl = Config::get('app.url'); // Get the base URL from config
    $employeeUrl = $baseUrl . '/employee/qr/' . $employeeNameSlug . '/' . $encryptedId . '-' . $randomString;

    // Pass the data to the Inertia page
    return Inertia::render('Employee/ServiceLetter', [
        'employee' => $employee,
        'service_letter_data' => $service_letter_data,
        'employeeUrl' => $employeeUrl, // Include the generated URL
    ]);
}

    public function ServiceLetter_store(Request $request)
    {

        // dd($request->all());

        $request->validate([
            'employee_id' => 'required|integer',
            'editorContent' => 'required',

        ]);
        // dd($request);

        try {


            DB::beginTransaction();

            $service = new Service();
            $service->employee_id = $request->employee_id;
            $service->letter = $request->editorContent;



            $service->save();

            DB::commit();


            return redirect()->route('employee.ServiceLetter.view', ['id' => $request->employee_id]);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function ServiceLetter_update(Request $request)
    {

        $request->validate([
            'employee_id' => 'required|integer',

        ]);
        // dd($request->all());

        try {
            DB::beginTransaction();
            // Fetch the  details using the data ID
            $service = Service::find($request->id);
            // $service->employee_id = $request->employee_id;
            $service->letter = $request->editorContent;
            $service->save();

            DB::commit();


            return redirect()->route('employee.ServiceLetter.view', ['id' => $request->employee_id]);
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function ServiceLetter_view($id)
{

    $employee = Employee::find($id);


    $service_letter_data = Service::where(['employee_id' => $id])->first();

    // Generate a random string for additional uniqueness
    $randomString = Str::random(10);
    $employeeNameSlug = Str::slug($employee->name);


    $baseUrl = Config::get('app.url');

    // Generate the employee URL to encode in the QR code
    $employeeUrl = $baseUrl . '/employee/qr/' . $employeeNameSlug . '/' . base64_encode($id) . '-' . $randomString;


    return Inertia::render('Employee/ServiceLetterView', [
        'employee' => $employee,
        'service_letter_data' => $service_letter_data,
        'employeeUrl' => $employeeUrl // Pass the QR code data to the front-end
    ]);
}


    // public function toggleServiceLetter($id)
    // {
    //     // Find the service for the employee using employee_id
    //     $service = Service::where('employee_id', $id)->first();

    //     // Check if the service exists
    //     if ($service) {
    //         // Toggle the visibility column
    //         $service->service_letter_visible = !$service->service_letter_visible;
    //         $service->save();

    //         // Redirect back with a success message
    //         return redirect()->back()->with('success', 'Service letter visibility updated.');
    //     }

    //     return redirect()->back()->with('error', 'Service not found.');
    // }




    // Fetch all employee projects for the admin
    public function adminProjects($employeeId)
    {
        $projects = UserData::where('employee_id', $employeeId)
            ->where('status', 0) // Optional: Only pending projects
            ->get();

        return Inertia::render('Employee/Projects', [
            'projects' => $projects,
        ]);
    }






    public function approveProject($id)
    {
        // Fetch the project by ID
        $project = UserData::find($id);

        // Check if the project exists
        if (!$project) {
            return response()->json(['error' => 'Project not found'], 404);
        }

        // Update the project status to 'approved'
        $project->status = 1; // Approved
        $project->save();

        // Fetch the employee details
        $employee = $project->employee; // Assuming there's a relationship between UserData and Employee
        if ($employee && $employee->email) {
            // Send an email to the employee
            Mail::to($employee->email)->send(
                new EmployeeProjectApprovedMail($employee->name, $project->project_name)
            );
        }

        return response()->json(['message' => 'Project approved successfully']);
    }
}
