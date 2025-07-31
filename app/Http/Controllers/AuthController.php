<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    // In routes/web.php (or api.php, depending on your use case)
    // Logout API
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            $token->revoke();

            return response()->json([
                'message' => 'Successfully logged out',
                'revoked' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get Authenticated User
    public function user(Request $request)
    {
        try {
            return response()->json([
                'user' => $request->user()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Unable to fetch user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // // ✅ Update User Profile
    // public function update(Request $request)
    // {
    //     try {
    //         $user = $request->user();

    //         $validator = Validator::make($request->all(), [
    //             'name' => 'sometimes|string|max:255',
    //             'email' => 'sometimes|email|unique:users,email,' . $user->id,
    //             // Add other fields here as needed
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'message' => 'Validation failed',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         // Update only passed fields
    //         $user->fill($request->only(['name', 'email']));
    //         $user->save();

    //         return response()->json([
    //             'message' => 'Profile updated successfully',
    //             'user' => $user
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Update failed',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }


//   public function update(Request $request)
//     {

//         try {
//             $user = $request->user();

//         $validator = Validator::make($request->all(), [
//             'name'           => 'sometimes|nullable|string|max:255',
//             'phone'          => 'sometimes|nullable|string|max:20',
//             'email'          => 'sometimes|nullable|email|unique:users,email,' . $user->id,
//             'place'          => 'sometimes|nullable|string|max:255',
//             'address'        => 'sometimes|nullable|string',
//             'gender'         => 'sometimes|nullable|string|in:Male,Female,Other',
//             'date_of_birth'  => 'sometimes|nullable|date',
//             'qualification'  => 'sometimes|nullable|string|max:255',
//             'experience'     => 'sometimes|nullable|string|max:255',
//             'expertise'      => 'sometimes|nullable|string',
//             'bankName'       => 'sometimes|nullable|string|max:255',
//             'accountHolder'  => 'sometimes|nullable|string|max:255',
//             'accountNumber'  => 'sometimes|nullable|string|max:255',
//             'ifsc'           => 'sometimes|nullable|string|max:255',
//             'branch'         => 'sometimes|nullable|string|max:255',
//             'profile_image'  => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
//             'newPassword'    => 'sometimes|nullable|string|min:8|confirmed',
//         ]);


//             if ($validator->fails()) {
//                 return response()->json([
//                     'message' => 'Validation failed',
//                     'errors' => $validator->errors()
//                 ], 422);
//             }

//             // Handle profile image upload
//             // return($request->profile_image());
//             // return response()->json([
//             //     'has_file' => $request->hasFile('profile_image'),
//             //     'file_info' => $request->file('profile_image')
//             // ]);

//             if ($request->hasFile('profile_image')) {
//                 // Delete old image if exists
//                 if ($user->image) {
//                     Storage::delete('public/profile_images/' . $user->image);
//                 }
                
//                 // Store new image
//                 $image = $request->file('profile_image');
//                 $imageName = time() . '-' . uniqid() . '.' . $image->extension();
//                 $image->storeAs('public/profile_images', $imageName);
                
//                 // Save to 'image' column
//                 $user->image = $imageName;
//             }

//             // Update user fields
//             $fields = [
//                 'name', 'phone', 'email', 'place', 'address', 'gender', 'date_of_birth',
//                 'qualification', 'experience', 'expertise', 'bank_name', 'account_holder_name',
//                 'account_number', 'ifsc_code', 'branch'
//             ];
            
//             foreach ($fields as $field) {
//                 if ($request->has($field)) {
//                     $user->$field = $request->input($field);
//                 }
//             }

//             // Handle password update
//             if ($request->filled('newPassword')) {
//                 $user->password = Hash::make($request->input('newPassword'));
//             }

//             $user->save();

//             // Return image URL
//             $imageUrl = null;
//             if ($user->image) {
//                 $imageUrl = url('storage/profile_images/' . $user->image);
//             }

//             return response()->json([
//                 'message' => 'Profile updated successfully',
//                 'user' => $user,
//                 'image_url' => $imageUrl
//             ]);
            
//         } catch (\Exception $e) {
//             return response()->json([
//                 'message' => 'Update failed',
//                 'error' => $e->getMessage()
//             ], 500);
//         }
//     }
    public function update(Request $request)
        {
            try {
                $user = $request->user();

                $validator = Validator::make($request->all(), [
                    'name'           => 'sometimes|nullable|string|max:255',
                    'phone'          => 'sometimes|nullable|string|max:20',
                    'email'          => 'sometimes|nullable|email|unique:users,email,' . $user->id,
                    'place'          => 'sometimes|nullable|string|max:255',
                    'address'        => 'sometimes|nullable|string',
                    'gender'         => 'sometimes|nullable|string|in:Male,Female,Other',
                    'date_of_birth'  => 'sometimes|nullable|date',
                    'qualification'  => 'sometimes|nullable|string|max:255',
                    'experience'     => 'sometimes|nullable|string|max:255',
                    'expertise'      => 'sometimes|nullable|string',
                    'bankName'       => 'sometimes|nullable|string|max:255',
                    'accountHolder'  => 'sometimes|nullable|string|max:255',
                    'accountNumber'  => 'sometimes|nullable|string|max:255',
                    'ifsc'           => 'sometimes|nullable|string|max:255',
                    'branch'         => 'sometimes|nullable|string|max:255',
                    'profile_image'  => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'newPassword'    => 'sometimes|nullable|string|min:8|confirmed',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                // Handle profile image upload (Updated)
                if ($request->hasFile('profile_image')) {
                    // Delete old image if exists
                    if ($user->image) {
                        Storage::delete('public/profile_images/' . $user->image);
                    }

                    // Store new image
                    $image = $request->file('profile_image');
                    $imageName = time() . '-' . uniqid() . '.' . $image->extension();
                    $image->storeAs('public/profile_images', $imageName);

                    // Save to 'image' and 'image_url' columns
                    $user->image = $imageName;
                    $user->image_url = asset('storage/profile_images/' . $imageName);
                }

                // Update user fields
                $fields = [
                    'name', 'phone', 'email', 'place', 'address', 'gender', 'date_of_birth',
                    'qualification', 'experience', 'expertise', 'bank_name', 'account_holder_name',
                    'account_number', 'ifsc_code', 'branch'
                ];

                foreach ($fields as $field) {
                    if ($request->has($field)) {
                        $user->$field = $request->input($field);
                    }
                }

                // Handle password update
                if ($request->filled('newPassword')) {
                    $user->password = Hash::make($request->input('newPassword'));
                }

                $user->save();

                return response()->json([
                    'message' => 'Profile updated successfully',
                    'user' => $user
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Update failed',
                    'error' => $e->getMessage()
                ], 500);
            }
        }

}
