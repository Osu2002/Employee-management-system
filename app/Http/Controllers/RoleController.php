<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Roles/Index');
    }

    public function getData()
    {
        $users = Role::all();

        return DataTables::of($users)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('action',  function ($row) {

                if ($row->name == 'Super Admin') {
                    return '';
                }

                $action_html = '';
                if ($row->name != 'Property Group Admin' && $row->name != 'Property Admin' ) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('users.edit')) {
                    $action_html .= '<a class="dropdown-item action_permissions" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-key"></i> Permissions</a>';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('users.delete')) {
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
                if ($row->status == 1) {
                    return '<span class="badge badge-success badge-pill">Active</span>';
                } else {
                    return '<span class="badge badge-danger badge-pill">Inactive</span>';
                }
            })
            ->rawColumns(['check', 'action', 'status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $allPermissions = $this->getAllPermissions();

        return Inertia::render('Roles/CreateUpdate', ['allPermissions' => $allPermissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'unique:roles,name'],
        ]);

        try {
            DB::beginTransaction();
            Role::create(['guard_name' => 'web', 'name' => $request->role_name]);
            DB::commit();
            return redirect(route('settings.roles'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            abort(500);
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
        $role = Role::find($id);
        return Inertia::render('Roles/CreateUpdate', ['role' => $role]);
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
            'role_name' => ['required', 'unique:roles,name,' . $request->id],
        ]);

        try {
            DB::beginTransaction();
            $role = Role::find($request->id);
            $role->name = $request->role_name;
            $role->save();
            DB::commit();
            return redirect()->route('settings.roles');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            Log::error($ex);
            abort(500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPermissions($id)
    {
        $role = Role::with('permissions')->find($id);
        $allPermissions = $this->getAllPermissions();
        return Inertia::render('Roles/Permissions', ['role' => $role, 'allPermissions' => $allPermissions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePermissions(Request $request)
    {
        try {
            DB::beginTransaction();
            $role = Role::find($request->id);
            $role->syncPermissions($request->permissions);
            DB::commit();
            return redirect()->route('settings.roles');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            abort(500);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Role::destroy([$request->ids]);
            return redirect()->route('settings.roles');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    public function getAllPermissions()
    {
        return Permission::select(
            'id',
            'section_name',
            'name',
            DB::raw("CONCAT(UPPER(SUBSTRING(REPLACE(name, '.', ' '),1,1)),LOWER(SUBSTRING(REPLACE(name, '.', ' '),2))) as ft_name")
        )->get()->groupBy('section_name');
    }
}
