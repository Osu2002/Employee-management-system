<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Currency/Index');
    }

    public function getData()
    {
        $data = Currency::all();

        return DataTables::of($data)
            ->addColumn('check', function ($row) {
                return '<div class="custom-control custom-checkbox item-check">
            <input type="checkbox" class="form-check-input" id="' . $row->id . '" value="' . $row->id . '">
            <label class="form-check-label form-check-label" for="' . $row->id . '"></label>
          </div>';
            })
            ->addColumn('action',  function ($row) {
                $action_html = '';
                if (auth()->user()->can('currency.view') && auth()->user()->can('currency.edit')) {
                $action_html .= '<a class="dropdown-item action_edit" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" href="javascript:void(0)"><i class="fas fa-edit mr-2"></i> View / Edit</a>';
                }
                if (auth()->user()->can('currency.edit')) {
                $action_html .= '<a class="dropdown-item ' . ($row->status == 1 ? 'text-warning' : 'text-success') . ' action_status_change" style="font-size: 14px;padding: 5px 13px;" data-item-id="' . $row->id . '" data-status="' . $row->status . '" href="javascript:void(0)"><i class="fas fa-power-off mr-2"></i>' . ($row->status == 1 ? ' Deactivate' : ' Activate') . '</a> ';
                }
                $action_html .= '<div class="dropdown-divider"></div>';
                if (auth()->user()->can('currency.delete')) {
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
            })->addColumn('symbol_pos', function ($row) {
                if ($row->symbol_pos == 'before') {
                    return 'Before';
                } else if($row->symbol_pos == 'after') {
                    return 'After';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-success">Active</span>';
                } else {
                    return '<span class="badge bg-warning">Inactive</span>';
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
        return Inertia::render('Currency/CreateUpdate');
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
            'name' => ['required', 'unique:currencies,name'],
            'symbol' => ['required'],
            'decimal_places' => ['required'],
            'symbol_pos' => ['required'],
            'status' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $model = new Currency();
            $model->name = $request->name;
            $model->symbol = $request->symbol;
            $model->decimal_places = $request->decimal_places;
            $model->symbol_pos = $request->symbol_pos;
            $model->status = $request->status;
            $model->save();
            DB::commit();
            return redirect(route('currency.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            abort(500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency = Currency::find($id);
        return Inertia::render('Currency/CreateUpdate', ['currency'=>$currency]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:currencies,name,'.$request->id],
            'symbol' => ['required'],
            'decimal_places' => ['required'],
            'symbol_pos' => ['required'],
            'status' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $model = Currency::find($request->id);
            $model->name = $request->name;
            $model->symbol = $request->symbol;
            $model->decimal_places = $request->decimal_places;
            $model->symbol_pos = $request->symbol_pos;
            $model->status = $request->status;
            $model->save();
            DB::commit();
            return redirect(route('currency.index'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            abort(500);
        }
    }

    public function updateStatus(Request $request)
    {
        // dd($request->all());
        try {
            $user = Currency::find($request->id);
            if ($request->status == 0) {
                $user->status = 1;
            } else {
                $user->status = 0;
            }
            $user->save();

                return redirect()->route('currency.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            Currency::destroy([$request->ids]);
            return redirect()->route('currency.index');
        } catch (Exception $ex) {
            Log::error($ex);
            return abort(500);
        }
    }
}
