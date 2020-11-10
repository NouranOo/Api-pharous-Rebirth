<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use validator;
use App\Interfaces\UserInterface;
class UserController extends Controller
{
    public $user;
    public $apiResponse;
    /**
     * Create a new controller instance.
     *t
     * @return void
     */
    public function __construct(UserInterface $user ,ApiResponse $apiResponse)
    {
        //
        $this->user = $user;
        $this->apiResponse = $apiResponse;
    }
    public function welcome(){
        return $this->user->welcome();
    }
    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'phone' => 'required|unique:users',
            'password' => 'required',
            'email' =>'required',
            'ApiKey' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->all();

        $result = $this->user->register($data);
        return $result->send();

    }
    public function login(Request $request)
    {
        $rules = [

            'phone' => 'required',
            'password' => 'required',
            'ApiKey' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }

        $data = $request->all();
        $result = $this->user->login($data);
        return $result->send();


    }
    public function getAllPlaces(Request $request)
    {
        $rules = [
            'ApiToken'=>'required',
            'ApiKey' => 'required',
            'city_id' => 'required',
        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }

        $data = $request->all();
        $result = $this->user->getAllPlaces($data);
        return $result->send();

    }
    public function addPlace(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'name' => 'required',
            'rate' => 'required',
            'photo' =>'',
            'city_id'=>'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {

            $file = $request->file("photo");
            $filename = str_random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/PlacePhotos';
            $file->move($path, $filename);
            $data['photo'] = $path . '/' . $filename;
        }


        $result = $this->user->addPlace($data);
        return $result->send();

    }
    public function uploadphoto(Request $request)
    {
        $rules = [

            'photo' => 'required',
            'ApiKey' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->except('photo');

        if ($request->hasFile('photo')) {

            $file = $request->file("photo");
            $filename = str_random(6) . '_' . time() . '_' . $file->getClientOriginalName();
            $path = 'ProjectFiles/UserPhotos';
            $file->move($path, $filename);
            $data['photo'] = $path . '/' . $filename;
        }

        $result = $this->user->uploadphoto($data);
        return $result->send();

    }
    public function editprofile(Request $request)
    {
        $rules = [
            'name' => '',
            'phone' => '',
            'password' => '',
            'email' => '',
            'ApiToken'=>'required',
            'ApiKey' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->all();

        $result = $this->user->editprofile($data);
        return $result->send();

    }
    public function forgetPassword(Request $request)
    {
        $rules = [
            'email' => 'required',
            'ApiKey' => 'required',


        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }
        $api_key = env('APP_KEY');
        if ($api_key != $request->ApiKey) {
            return $this->apiResponse->setError("Unauthorized!")->send();
        }
        $data = $request->all();

        $result = $this->user->forgetPassword($data);
        return $result->send();

    }
    public function addCity(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'name' => 'required',

        ];

        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();

        }

        $data = $request->all();
        $result = $this->user->addCity($data);
        return $result->send();


    }
    public function getPhotosOfPlaces(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
        ];
        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();
        }

        $data = $request->all();
        $result = $this->user->getPhotosOfPlaces($data);
        return $result->send();

    }
    public function showAPlace(Request $request)
    {
        $rules = [
            'ApiKey' => 'required',
            'place_id' =>'required',
        ];
        $validation = Validator::make($request->all(), $rules);

        if ($validation->fails()) {
            return $this->apiResponse->setError($validation->errors()->first())->send();
        }

        $data = $request->all();
        $result = $this->user->showAPlace($data);
        return $result->send();

    }



}
