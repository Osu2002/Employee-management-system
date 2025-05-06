<?php

namespace App\Http\Controllers;

use App\Jobs\DeleteRandomNumber;
use App\Mail\OTPMail;

use App\Models\Document;
use App\Models\EmpLeave;
use App\Models\Employee;
use App\Models\Leaves;
use App\Models\Service;
use App\Models\RandomNumber;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use PhpParser\Node\Stmt\Else_;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Add this import to use Hash
use Illuminate\Support\Facades\Log;

class EmployeeFrontendController extends Controller
{
    public function index(Request $request)
    {
        //$user = Auth::user(); // This gives the logged-in user

        // dd($request->all());

        return Inertia::render('Home/Index', ['message' => $request->message]);
    }

    public function frontlogin()
    {

        return Inertia::render('Home/Login');
    }

    public function processLogin(Request $request)
    {
        // Validate login input
        $request->validate([
            'index' => 'required|regex:/^EMP\d+$/',
            'password' => 'required|string|min:6',
        ]);


        $rawIndex = str_replace('EMP', '', $request->index); // Remove 'EMP'
        $processedIndex = (int)$rawIndex - 5000; // Convert to integer and subtract 5000

        // dd($processedIndex);
        // Extract numeric part of Employee ID
        $credentials = [
            'id' => $processedIndex,
            'password' => $request->input('password'),
        ];

        // Find employee by id
        $employee = Employee::where(['id' => $credentials['id'], 'status' => 1])->first();
        // dd($employee);
        // ;Authenticate employee using guard
        if ($employee && Hash::check($credentials['password'], $employee->password)) {
            Auth::guard('employee')->login($employee);
            // $request->session()->regenerate();

            // Redirect to profile details page
            // $encryptedEmpId = Crypt::encryptString($employee->id);
            return redirect()->route('profiledetails');
        }

        // If authentication fails
        return back()->withErrors(['error' => 'Invalid credentials or account is inactive.']);
    }



    //Display employee datails
    public function profiledetails(Request $request, $slug = null , $id = null)

    {
        // dd($id);
        $parts = explode('-', $id);
        $encryptedId = $parts[0];
// dd($encryptedId);
        try {
            if ($encryptedId) {
                
                $emp_id = base64_decode($encryptedId);
            } else {

                $emp_id = auth()->guard('employee')->user()->id;
            }

            // Include the jobRole relationship
            $employee_data = Employee::with(['media', 'jobRole'])->findOrFail($emp_id);
            //dd($employee_data->toArray());
            $leave_data_old = Leaves::where('employee_id', $employee_data->id)->get();
            $service_letter_data = Service::where(['employee_id' => $employee_data->id])->first();
            $leave_data = EmpLeave::where('employee_id', $employee_data->id)->get();
            $document_data = Document::where(['employee_id' => $employee_data->id, 'display_to_employee' => 1])
                ->with('media')
                ->get();

            return Inertia::render('Home/Home', [
                'employee_data' => $employee_data,
                'leave_data' => $leave_data,
                'leave_data_old' => $leave_data_old,
                'document_data' => $document_data,
                'user' => $employee_data, // Add employee as user
                'service_letter_data' =>  $service_letter_data,
            ]);
        } catch (DecryptException $e) {
            return redirect()->route('index')->withErrors(['message' => 'Invalid employee ID.']);
        } catch (Exception $e) {
            return redirect()->route('index')->withErrors(['message' => 'Error fetching profile details.']);
        }
    }


    public function employeelogout()
    {
        Log::info(Auth::user());
        Auth::guard('employee')->logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        return redirect()->route('frontlogin')->with('success', 'You have been logged out.');
    }


    private function generateSixDigitRandomNumber()
    {
        return mt_rand(100000, 999999);
    }
}
