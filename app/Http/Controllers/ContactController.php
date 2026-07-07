<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\Group;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::paginate(10);

        return view("contacts.list",compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $groups = Group::limit(20)->get();
        return view("contacts.create",compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(StoreContactRequest $request)
{


    $contact = Contact::create($request->validated());
    

    if($contact){

        return response()->json([
            'status' => 200,
            'message' => 'Contact added successfully.',
            'contact' => $contact,
            ], 201);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Unable to Add contact.',
            ]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::find($id);
        $groups = Group::limit(20)->get();
        if(!$contact){
            return abort(404,"Contact Not Found!");
        }
        return view("contacts.edit",compact('contact','groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request)
    {
        $data = $request->validated();

        $contact = Contact::find($request->id);
        
        $contact->update($data);
        $contact->save();

        return response()->json([
            'status'=>200,
            'message'=>'Contact Updated Successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        if(!$contact){
            return response()->json([
                'status'=>404,
                'message'=>'Not Found!'
                ]);
                }
                
            $contact->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Contact Deleted Successfully!'
            ]);

    }
}
