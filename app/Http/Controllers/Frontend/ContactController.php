<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactConfirmation;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function getContacts()
    {
        try {
            $contact = Contact::all();
            return response()->json(['message' => 'Contact list Fetched Successfully', 'contact' => $contact, 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function contactMe(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors(), 'status' => 422], status: 422);
            }
            $contact = Contact::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ]);
            Mail::to($request->input('email'))->send(new ContactConfirmation($contact));
            
            return response()->json(['message' => 'Stay connected', 'contact' => $contact, 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }

    public function deleteContact($id)
    {
        try {
            $contact = Contact::find($id);
            if (!$contact) {
                return response()->json(['message' => 'Contact not found', 'status' => 404], 404);
            }
            $contact->delete();
            return response()->json(['message' => 'Contact Deleted Successfully', 'status' => 200], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage(), 'status' => 500], status: 500);
        }
    }
}
