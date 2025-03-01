<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        //check if technician exists from technician table

        if (Auth::guard('technician')->attempt($credentials)) {
            $technician = Auth::guard('technician')->user();
            $token = $technician->createToken('authToken')->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }

    public function user(){
        $technician = auth('sanctum')->user() ;
        return response()->json(['technician' => $technician], 200);

    }

    public function testConnection(Request $request)
    {
        return response()->json(['message' => 'Connection successful'], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
    public function sendVerificationCode(Request $request)
    {
        // Ensure phone_number is provided
        if (!$request->has('phone_number')) {
            return response()->json(['message' => 'Phone number is required'], 400);
        }

        // Check if the technician exists
        $technician = Technician::where('phone_number', $request->phone_number)->first();

        if (!$technician) {
            // Create a new technician with a verification code
            $verification_code = rand(1000, 9999);
            $technician = Technician::create([
                'phone_number' => $request->phone_number,
                'phone_verification_code' => $verification_code,
            ]);
        } else {
            // Update the technician's phone number and verification code
            $verification_code = rand(1000, 9999);
            //$technician->phone_number = $request->phone_number;
            $technician->phone_number = $request->phone_number;
            $technician->phone_verification_code = $verification_code;
            $technician->save();
        }

        // Twilio configuration
        $account_sid = config('services.twilio.sid');
        $auth_token = config('services.twilio.token');
        $twilio_number = config('services.twilio.phone_number');

        //use config file to get the twilio credentials
        $message = "Welcome to our platform. Your verification code is: $verification_code.";
        $recipients = $request->phone_number;
        //$recipients = '+962776802827';

        // Send the SMS
        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($recipients, [
                'from' => $twilio_number,
                'body' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to send SMS', 'error' => $e->getMessage()], 500);
        }

        // Generate a token for the technician
        $token = $technician->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'SMS Sent',
            'user' => $technician,
            'verification_code' => $technician->phone_verification_code,
            'token' => $token,
        ], 200);
    }

    public function verifyVerificationCode(Request $request)
    {
        $technician = Auth::guard('sanctum')->user();
        if ($technician->phone_verification_code == $request->code) {
            $technician->phone_verified = true;
            $technician->phone_verified_at = now();
            $technician->save();
        $technician->phone_verification_code = rand(1000, 9999);
        $technician->save();
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_number = getenv("TWILIO_NUMBER");
        $verification_code = rand(1000, 9999);
        $message = "Welcome to our platform. Your verification code is: $verification_code.";
        //$recipients = $request->phone_number;

        $recipients = '+962776802827';
        //$client = new Client($account_sid, $auth_token);
//        $client->messages->create($recipients,
//            ['from' => $twilio_number, 'body' => $message]
//        );
        return response()->json(['message' => 'Sms Sent'], 200);}
        else {
            return response()->json(['message' => 'Invalid code'], 401);
        }
    }

    public function updateProfile(Request $request)
    {
        $technician = Auth::guard('sanctum')->user();
        $technician->update([
            'full_name' => $request->full_name,
            'email' => $request->email,
            //'phone_number' => $request->phone_number,
            //'address' => $request->address,
           // 'city' => $request->city,
            //'country' => $request->country,
            //'zip_code' => $request->zip_code,
            //'profile_picture' => $request->profile_picture,
            ]);
        return response()->json(['message' => 'Profile updated'], 200);
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'full_name' => 'required|max:55',
                'email' => 'email|required|unique:users',
                'password' => 'required'
            ]);
            $validatedData['password'] = bcrypt($request->password);
            $technician = Auth::guard('sanctum')->user();
            $technician->update($validatedData);
            //dd($validatedData);
           // $technician = Technician::update($validatedData);

        } catch (\Exception $e) {
            //if email  is duplicated return error 409
            if ($e->getCode() == 23000) {
                return response()->json(['message' => $e->getMessage()], 409);
            }

            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function profile()
    {
        try {
            //
           // $technician = Auth::guard('sanctum')->user()->with('workorders')->get();

           //Get the authenticated technician with the total orders completed and the pending jobs for the authenticated technichian

            $technician  = Auth::guard('sanctum')->user();
            $completed = Workorder::where('assigned_to', $technician->id)->where('current_status', 'completed')->count();
            $pending = WorkOrder::where('assigned_to', $technician->id)->where('current_status', 'pending')->count();
            $technician['completed'] = $completed;
            $technician['pending'] = $pending;

            // Check if the technician is authenticated
            if (!$technician) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Return the technician's profile details
            return response()->json([
                'success' => true,
                'message' => 'Technician profile retrieved successfully.',
                'data' => $technician
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
