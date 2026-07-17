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
    public function index(Request $request)
    {

    $records = min($request->integer('records',10),100);
   
        $contacts = Contact::query()->when($request->filled("searchQuery"), function($query) use($request){
            
            $searchQuery = $request->input("searchQuery");

            $query->where('name','like',"%{$searchQuery}%")
            ->orWhere('email','like',"%{$searchQuery}%")
            ->orWhereHas("group",function($relationQuery) use($searchQuery){
                $relationQuery->where("name","like","%{$searchQuery}%");
            });


        })->when($request->filled("sortBy"),function($query) use($request){
            $sortBy = $request->input("sortBy","created_at");
            $direction = $request->input("sortDirection","desc");

            $query->orderBy($sortBy,$direction);

        },
         function($query){
                $query->orderBy("created_at","desc");
            }
        
        )->with('group')->paginate($records);


        if(!$request->ajax()){
            return view("contacts.list", compact('contacts'));
        }

        return response()->json([
                'status'=>200,
                'searchQuery'=>$request->input("searchQuery"),
                'sortBy'=> $request->input("sortBy"),
                'sortDirection'=> $request->input("sortDirection"),
                'records'=>$records,
                'message'=>'Data Fetched Successfully!',
                'contacts'=>$contacts,
                'request'=>$request->all()
            ],200);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $groups = Group::limit(20)->get();
        if(!$contact){
            return abort(404,"Contact Not Found!");
        }
        return view("contacts.edit",compact('contact','groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $data = $request->validated();
        $contact->update($data);

        return response()->json([
            'status'=>200,
            'contact'=>$contact,
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
                'message'=>'Contact Not Found!'
                ],404);
            }
                
            $contact->delete();
            return response()->json([
                'status'=>200,
                'message'=>'Contact Deleted Successfully!'
            ]);

    }

    public function searchGroup(Request $request){

    $groups = Group::query()
        ->when($request->filled('searchGroup'), function($query) use($request){
     
            $searchGroup = $request->input("searchGroup");

            $query->where('name','like',"%{$searchGroup}%");

        })->limit(5)->get();

    
    return response()->json([
            'status'=>200,
            'message'=>'Groups Fetched Successfully!',
            'groups'=>$groups,
            'query'=>$request->input('searchGroup')
        ],200);
    }
}
