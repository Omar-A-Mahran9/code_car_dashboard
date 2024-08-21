<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Massage;
use Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    
        // $this->authorize('view_chats');

        // if ($request->ajax())
        // {
        //     $messages = getModelData( model: new ContactUs() );

        //     return response()->json($messages);

        // }

 // Retrieve all massages received by the authenticated user
        $receivedMassages = Massage::whereHas('recipients', function ($query) {
            $query->where('employee_id', auth()->id());
        })->with('sender')->orderBy('created_at', 'desc')
        ->get();
        $userId = Auth::id();
        $massages = Massage::where('from', $userId)->orderBy('created_at', 'desc')
        ->get();
        // Pass the received massages to the view
        return view('dashboard.chats.index', ['massages' => $massages,'receivedMassages' => $receivedMassages]);    }

    
    public function create()
    {
        $this->authorize('create_chats');
        $employees = Employee::all(); // Assuming you want to fetch all employees
        $receivedMassages = Massage::whereHas('recipients', function ($query) {
            $query->where('employee_id', auth()->id());
        })->with('sender')->orderBy('created_at', 'desc')
        ->get();
        $userId = Auth::id();
        $massages = Massage::where('from', $userId)->orderBy('created_at', 'desc')
        ->get();
        return view('dashboard.chats.create', ['employees' => $employees,'massages' => $massages,'receivedMassages' => $receivedMassages]);

    }

 
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'compose_to' => 'required|array',
            'compose_to.*' => 'exists:employees,id',
            'compose_subject' => 'required|string|max:255',
            'compose_contain' => 'required|string',
        ]);
        $from = Auth::user()->id;
        $data=[
            'from'=>$from,
            'compose_to'=>$request->compose_to,
            'compose_subject'=>$request->compose_subject,
            'compose_contain'=>$request->compose_contain,
            'is_read'=>0,
        ];
        $massage = Massage::create([
            'from' => $from,
            'subject' => $request->compose_subject,
            'content' => $request->compose_contain,
        ]);
        $massage->recipients()->attach($request->compose_to);

    }

 
    public function show($id)
    {
        $userId = Auth::id();
        $massages = Massage::where('from', $userId)->orderBy('created_at', 'desc')
        ->get();
        $receivedMassages = Massage::whereHas('recipients', function ($query) {
            $query->where('employee_id', auth()->id());
        })->with('sender')->orderBy('created_at', 'desc')
        ->get();
    
        return view('dashboard.chats.show', ['massages' => $massages,'receivedMassages' => $receivedMassages]);
    }
    public function showMessage($id){
        $massage=Massage::findOrFail($id);
        // $this->authorize('show_chats');
        $employees = Employee::all(); // Assuming you want to fetch all employees
        $selectedEmployees = $massage->recipients->pluck('id')->toArray();

        $receivedMassages = Massage::whereHas('recipients', function ($query) {
            $query->where('employee_id', auth()->id());
        })->with('sender')->get();
        $userId = Auth::id();
        $massages = Massage::where('from', $userId)->get();
        $massage->update([
            "is_read" => 1
        ]);
        return view('dashboard.chats.show-massages', ['selectedEmployees'=>$selectedEmployees,'massage'=>$massage,'employees' => $employees,'massages' => $massages,'receivedMassages' => $receivedMassages]);

     }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $massage=Massage::findOrFail($id);
        dd($massage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
