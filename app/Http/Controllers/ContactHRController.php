<?php

namespace App\Http\Controllers;

use App\Models\ContactHRMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\ContactHRNotification;
use App\Mail\ContactEmployeeConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmployeeReplyNotification;
use App\Mail\EmployeeReplyNotification;
use Illuminate\Support\Facades\Auth;

class ContactHRController extends Controller
{
    public function index()
    {
       
        return Inertia::render('Home/ContactHR');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            // Validate incoming request
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            // Save to the database

            $message = ContactHRMessage::create($validatedData);
            // Save the new reply
            $newReply = [
                'from' => 'employee',
                'message' => $request->input('message'),
                'timestamp' => now(),
            ];

            $replies = $message->emp_replies ?? [];
            $replies[] = $newReply;

            $message->emp_replies = $replies;
            $message->save();
            // Send mail to HR
            $hrEmail = 'hr@weblook.com';
            Mail::to($hrEmail)->send(new ContactHRNotification($message));

            // Send confirmation mail to the employee
            Mail::to($validatedData['email'])->send(new ContactEmployeeConfirmation($message));

            // Return a success response
            return redirect()->back();
        } catch (\Exception $e) {
       
            Log::error('Error saving Contact HR message: ' . $e->getMessage());
            return redirect()->back();
        }
    }
    // In ContactHRController
    public function getMessages()
    {
        $auth_employee = Auth::guard('employee')->check() ? auth()->guard('employee')->user() : null;
        // Log::info($auth_employee);

        if($auth_employee){
            $messages = ContactHRMessage::where(['email' => $auth_employee->email])->orderBy('created_at', 'desc')->get();
        }else{
            $messages = null;
        }
        return $messages;
    }
    public function adminIndex()
    {
        $messages = ContactHRMessage::orderBy('created_at', 'desc')->get();

        return Inertia::render('ContactHR/index', [
            'messages' => $messages,
        ]);
    }

    public function destroy($id)
    {
        
        $message = ContactHRMessage::findOrFail($id);
        $message->delete();

        return redirect()->route('contact.hr.index')->with('success', 'Message deleted successfully.');
    }



    /**
     * Fetch data for Contact HR messages DataTables.
     */
    public function getContactHRMessagesData()
    {
        try {
            $messages = ContactHRMessage::query(); // Replace `::all()` with a query builder to ensure pagination works.

            return DataTables::of($messages)
                ->addColumn('check', function ($row) {
                    return '<div class="custom-control custom-checkbox item-check">
                        <input type="checkbox" class="form-check-input" id="check-' . e($row->id) . '" value="' . e($row->id) . '">
                        <label class="form-check-label" for="check-' . e($row->id) . '"></label>
                    </div>';
                })
                ->addColumn('name', function ($row) {
                    return e($row->name);
                })
                ->addColumn('email', function ($row) {
                    return '<a href="mailto:' . e($row->email) . '">' . e($row->email) . '</a>';
                })
                ->addColumn('subject', function ($row) {
                    return e($row->subject);
                })
                ->addColumn('message', function ($row) {
                    return '<span title="' . e($row->message) . '">' . \Illuminate\Support\Str::limit(e($row->message), 50) . '</span>';
                })
                ->addColumn('date', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="btn-group">
                            <button class="btn btn-primary btn-sm action_reply" data-id="' . e($row->id) . '">Reply</button>
                            <button class="btn btn-danger btn-sm action_delete" data-id="' . e($row->id) . '">Delete</button>
                        </div>
                    ';
                })

                ->rawColumns(['check', 'email', 'message', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Error fetching Contact HR data: ' . $e->getMessage());
            return response()->json(['error' => 'Server error. Please check the logs for more details.'], 500);
        }
    }

    public function getMessagesWithReplies()
    {
        $messages = ContactHRMessage::all();
        return response()->json($messages);
    }
    public function getChatHistory($id)
    {
        try {
            $message = ContactHRMessage::findOrFail($id);

            // Combine admin and employee replies
            $combinedReplies = array_merge(
                $message->replies ?? [],
                $message->emp_replies ?? []
            );

            // Sort the combined replies by timestamp
            usort($combinedReplies, function ($a, $b) {
                return strtotime($a['timestamp']) - strtotime($b['timestamp']);
            });

            return response()->json([
                'success' => true,
                'chat_history' => $combinedReplies,
                'employee_message' => $message->message,
                'created_at' => $message->created_at,
                'employee_name' => $message->name, // Include employee name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }




    public function reply(Request $request, $id)
    {
        try {
            // Find the message by ID
            $message = ContactHRMessage::findOrFail($id);

            // Prepare the new reply
            $newReply = [
                'from' => 'admin',
                'message' => $request->input('reply'),
                'timestamp' => now(),
            ];

            // Append the reply to existing replies
            $replies = $message->replies ?? [];
            $replies[] = $newReply;

            $message->replies = $replies;
            $message->save();

            // Send email to the employee with the admin's reply
            $employeeEmail = $message->email;
            $mailDetails = [
                'employeeName' => $message->name,
                'subject' => $message->subject,
                'originalMessage' => $message->message,
                'adminReply' => $request->input('reply'),
            ];
            Mail::to($employeeEmail)->send(new ContactEmployeeReplyNotification($mailDetails));

            return response()->json(['success' => true, 'replies' => $replies]);
        } catch (\Exception $e) {
            Log::error('Error replying to Contact HR message: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reply.'], 500);
        }
    }




    public function employeeReply(Request $request, $id)
    {
        try {
            // Find the HR message
            $message = ContactHRMessage::findOrFail($id);

            // Save the new reply
            $newReply = [
                'from' => 'employee',
                'message' => $request->input('reply'),
                'timestamp' => now(),
            ];

            $replies = $message->emp_replies ?? [];
            $replies[] = $newReply;

            $message->emp_replies = $replies;
            $message->save();

            // Prepare email details
            $mailDetails = [
                'employeeName' => $message->name,
                'employeeEmail' => $message->email,
                'originalMessage' => $message->message,
                'replyMessage' => $request->input('reply'),
                'subject' => $message->subject,
            ];

            // Send email to HR
            $hrEmail = 'hr@weblook.com';
            Mail::to($hrEmail)->send(new EmployeeReplyNotification($mailDetails));

            $auth_employee = Auth::guard('employee')->check() ? auth()->guard('employee')->user() : null;
            // Log::info($auth_employee);
    
            if($auth_employee){
                $messages = ContactHRMessage::where(['email' => $auth_employee->email])->orderBy('created_at', 'desc')->get();
            }else{
                $messages = null;
            }
            return $messages;
        } catch (\Exception $e) {
            // Log error and return response
            Log::error('Error in employeeReply: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send reply.'], 500);
        }
    }

    public function chatHistory($id)
    {
        $message = ContactHRMessage::findOrFail($id);

        $replies = $message->replies ?? [];
        $empReplies = $message->emp_replies ?? [];

        return response()->json([
            'replies' => $replies,

            'emp_replies' => $empReplies,
        ]);
    }

    public function fetchChatHistory($id)
    {
        try {
            $message = ContactHRMessage::findOrFail($id);
            return response()->json([
                'replies' => $message->replies ?? [],

                'emp_replies' => $message->emp_replies ?? []

            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error fetching chat history'], 500);
        }
    }
}
