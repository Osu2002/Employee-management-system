<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Property;
use App\Models\ServiceType;
use App\Models\User;
use App\Models\Service;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class BackendUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('BackendUsers/Index');
    }

    public function getData()
    {
        $users = User::withTrashed()->get();

        return DataTables::of($users)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('role', function ($row) {
                return '<span class="badge bg-label-info">' . (count($row->getRoleNames()) > 0 ? $row->getRoleNames()[0] : "") . '</span>';
            })
            ->addColumn('action',  function ($row) {

                if ($row->hasRole('Super Admin')) {
                    return '';
                }

                $action_html = '';
                if (auth()->user()->can('backend-user.view backend-user.edit')) {
                $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('backend-user.edit')) {
                $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('backend-user.delete')) {
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
                } else if($row->deleted_at) {
                    return '<span class="badge bg-danger">Suspended</span>';
                }
            })
            ->rawColumns(['check', 'action', 'role', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $allRoles = Role::all();
        return Inertia::render('BackendUsers/CreateUpdate', ['allRoles' => $allRoles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //  dd($request);
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:backend_users,email'],
            'mobile_no' => ['nullable', 'unique:backend_users,mobile_no'],
            'role' => ['required'],
            'service_types' => ['nullable'],
            'branch' => ['nullable'],
            'password' => ['required', 'confirmed'],
            'profile_image' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);
// dd($request->all());
        try {
            DB::beginTransaction();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile_no = $request->mobile_no;
            $user->email_verified_at = now();
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->save();

            DB::commit();

            $role = Role::find($request->role);
            $user->assignRole($role->name);

            if ($request->hasFile('profile_image')) {
                $file = $user->addMedia($request->file('profile_image'))->toMediaCollection('avatar');
                $user->profile_photo_path = str_replace(config('app.url'), '', $file->getUrl());
                $user->save();
            }

            return redirect()->route('settings.users');
        } catch (Exception $ex) {
            dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $branches = Branch::all();
        // $service_types = ServiceType::where('status', 1)->get();
        $backendUser = User::withTrashed()->find($id)->load('roles');
        // dd($backendUser);
        $allRoles = Role::all();
        // dd($allRoles);
        return Inertia::render('BackendUsers/CreateUpdate', ['backendUser'=>$backendUser,'allRoles' => $allRoles]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:backend_users,email,'.$request->id],
            'mobile_no' => ['nullable', 'unique:backend_users,mobile_no,'.$request->id],
            'role' => ['required'],
        ]);
        // dd($request->all());
        try {
            DB::beginTransaction();

            $user = User::with('media')->find($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile_no = $request->mobile_no;
            $user->branch = $request->branch;
            // $user->password = $user->password;
            // $user->status = 1;
            $user->save();

            DB::commit();

            $role = Role::find($request->role);
            $user->syncRoles([$role->name]);

            if ($request->hasFile('profile_image')) {
                if($user->media) {
                    Storage::disk('public')->delete($user->media);
                    $user->clearMediaCollection('Avatars');
                }
                $file = $user->addMedia($request->file('profile_image'))->toMediaCollection('Avatars');
                $user->profile_photo_path = str_replace(config('app.url'), '', $file->getUrl());
                $user->save();
            }

            return redirect()->route('settings.users');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }
    public function updatePassword(Request $request) {
        $authUser = User::find(Auth::user()->id);
        $user = User::find($request->id);
        $this->validatePassword($request, $authUser, 'changePassword');

        $request->validate([
            'password' => ['required', 'string', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $user->password = Hash::make($request['password']);
        $user->save();
        return back();
    }

    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = User::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

                return redirect()->route('settings.users');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function softDelete(Request $request)
    {
        // dd($request->all());
        try {
            User::destroy([$request->ids]);
            return redirect()->route('settings.users');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function restore(Request $request)
    {
        $authUser = User::find(Auth::user()->id);
        $this->validatePassword($request, $authUser, 'restoreUser');

        try {
            $user = User::withTrashed()->find($request->id);
            $user->restore();
            return redirect()->route('settings.users');
            // return back();
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function delete(Request $request)
    {
        $authUser = User::find(Auth::user()->id);
        $this->validatePassword($request, $authUser, 'deleteUser');

        try {
            $user = User::withTrashed()->find($request->id);
            $user->forceDelete();
            return redirect()->route('settings.users');
            // return back();
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function validatePassword(Request $request, $user, $errorBag)
    {
        Validator::make($request->all(), [
            'confirm_password' => ['required', 'string'],
        ])->after(function ($validator) use ($request, $user) {
            if (!isset($request['confirm_password']) || !Hash::check($request['confirm_password'], $user->password)) {
                $validator->errors()->add('confirm_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag($errorBag);
    }

    // public function toggleVisibility(Request $request)
    // {
    //     $validated = $request->validate([
    //         'service_id' => 'required|exists:services,id', // Validate the service ID
    //         'visible' => 'required|boolean', // Validate the visibility flag
    //     ]);

    //     // Find the service record based on the provided service_id
    //     $service = Service::find($validated['service_id']);

    //     // Update the `service_letter_visible` column
    //     $service->service_letter_visible = $validated['visible'];
    //     $service->save();

    //     return response()->json(['message' => 'Visibility updated successfully']);
    // }
    public function toggleServiceLetter(Request $request, $id)
    {
        $status = $request->input('status'); // Expected true or false
        $service = Service::findOrFail($id); // Fetch specific service
        $service->service_letter_visible = $status;
        $service->save();

        return response()->json(['message' => 'Service letter visibility updated.']);
    }



}
