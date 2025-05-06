<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\FrontendUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class FrontendUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('FrontendUsers/Index');
    }

    public function viewCustomer(Request $request)
    {
        // dd($request);
        $customer = Customer::find($request->id);
        return Inertia::render('FrontendUsers/History', ['customer'=>$customer]);
    }

    public function getData()
    {
        $users = Customer::all();

        return DataTables::of($users)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('action',  function ($row) {

                $action_html = '';
                if (auth()->user()->can('frontend-user.view frontend-user.edit')) {
                $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('frontend-user.edit')) {
                $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('frontend-user.create')) {
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
            ->rawColumns(['check', 'action', 'status'])
            ->make(true);
    }

    public function getBookings(Request $request)
    {
        // dd($request);
        $customer = Customer::find($request->id);

        $bookings = [];
        foreach ($customer->activity as $key => $value) {
            if($value->type == 'booking') {
                $booking = Booking::find($value->type_id);
                array_push($bookings, $booking);
            }
        }
        // $bookings = Booking::where('email', $customer->email)->get();

        // dd($bookings);
        return DataTables::of($bookings)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('action',  function ($row) {
                $action_html = '';
                if (auth()->user()->can('booking.view') && auth()->user()->can('booking.edit')) {
                    $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
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
            ->addColumn('customer', function ($row) {
                return $row->customer_name;
            })

            ->addColumn('date_time', function ($row) {
                return $row->date . ' at ' . $row->time;
            })
            
            ->addColumn('total', function ($row) {
                return 'Rs. ' . $row->total . '.00 ';
            })

            ->addColumn('services', function ($row) {
                $services = '';
                foreach ($row->items as $key => $service) { 
                    $services .= '<div> + ' . ($service?->service?->name ? $service?->service?->name : " ") . '</div>';
                }
                return $services;
            })

            ->addColumn('status', function ($row) {
                if ($row->status == 'confirm') {
                    return '<span class="badge bg-success">Confirmed</span>';
                } else if ($row->status == 'cancel') {
                    return '<span class="badge bg-secondary">Cancled</span>';
                } else if ($row->status == 'pending') {
                    return '<span class="badge bg-primary">Pending</span>';
                } else if ($row->status == 'reject') {
                    return '<span class="badge bg-danger">Rejected</span>';
                } else if ($row->status == 'complete') {
                    return '<span class="badge bg-info">Completed</span>';
                } else {
                    return '<span class="badge bg-danger">Failed</span>';
                }
            })
            ->rawColumns(['check', 'action', 'status', 'customer', 'date_time', 'services', 'total'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('FrontendUsers/CreateUpdate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => ['required'],
            'dob' => ['required'],
            'email' => ['required', 'unique:customers,email'],
            'phone' => ['nullable', 'unique:customers,phone'],
            'password' => ['required', 'confirmed'],
            // 'avatar' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);

        // dd($request->all());

        try {
            DB::beginTransaction();

            $user = new Customer();
            $user->name = $request->name;
            $user->dob = $request->dob;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->save();

            DB::commit();

            // dd($request->file);

            // if ($request->hasFile('avatar')) {
            //     $user->addMedia($request->file('avatar'))->toMediaCollection('Customers');
            // }

            return redirect()->route('customer.index');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FrontendUser  $frontendUser
     * @return \Illuminate\Http\Response
     */
    public function show(FrontendUser $frontendUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FrontendUser  $frontendUser
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $frontendUser = Customer::find($id);
        return Inertia::render('FrontendUsers/CreateUpdate', ['frontendUser'=>$frontendUser]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FrontendUser  $frontendUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'dob' => ['required'],
            'email' => ['required', 'unique:customers,email,'.$request->id],
            'phone' => ['nullable', 'unique:customers,phone,'.$request->id],
            // 'avatar' => ['nullable', 'mimes:jpeg,jpg,png,webp', 'max:10000']
        ]);

        // dd($request->all());

        try {
            DB::beginTransaction();

            $user = Customer::find($request->id);
            $user->name = $request->name;
            $user->dob = $request->dob;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->save();

            DB::commit();

            // if ($request->hasFile('avatar')) {
            //     $user->addMedia($request->file('avatar'))->toMediaCollection('Customers');
            // }

            return redirect()->route('customer.index');
        } catch (Exception $ex) {
            // dd($ex);
            DB::rollBack();
            return abort(500);
        }
    }

    public function updatePassword(Request $request) {
        $authUser = User::find(Auth::user()->id);
        $user = Customer::find($request->id);
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
            $user = Customer::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

            return redirect()->route('customer.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FrontendUser  $frontendUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // dd($request->all());
        try {
            Customer::destroy([$request->ids]);
            return redirect()->route('customer.index');
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
}
