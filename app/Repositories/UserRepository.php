<?php
namespace App\Repositories;
use App\Models\city;
use Pnlinh\GoogleDistance\Facades\GoogleDistance;
use App\Interfaces\UserInterface;
use App\Helpers\FCMHelper;
use App\Helpers\ApiResponse;
use App\Helpers\GeneralHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\place;
use App\Models\Notfication;
use DB;
use Illuminate\Support\Facades\Hash;
use function foo\func;
class UserRepository implements UserInterface
{
    public $apiResponse;
    public $generalhelper;
    public function __construct(GeneralHelper $generalhelper, ApiResponse $apiResponse){
        $this->generalhelper = $generalhelper;

        $this->apiResponse = $apiResponse;


    }
    public function welcome(){
        return "Hello";
    }
    public function register($data){

        try{
            $data['ApiToken'] = base64_encode(str_random(40));
            $data['password'] = app('hash')->make($data['password']);
            $user = new user();
            $user->name = $data['name'];
            $user->phone = $data['phone'];
            $user->password = $data['password'];
            $user->email = $data['email'];

            $user->ApiToken = $data['ApiToken'];
            $user->save();
            $newUSer = user::where('id',$user->id)->first();

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("User created succesfully")->setData($newUSer);

    }
    public function login($data)
    {
        try{
            $user = user::where('phone',$data['phone'])->first();
            if($user){
                $check = Hash::check($data['password'], $user->password);
                if ($check) {

                    $user->update(['ApiToken' => base64_encode(str_random(40))]);
                    $user->save();

                }else{
                    return $this->apiResponse->setError("Your Password Not correct")->setData();
                }

            }else{
                return $this->apiResponse->setError("Your Phone Not Found")->setData();
            }


        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data", $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Login succesfully")->setData($user);


    }
    public function getAllPlaces($data)
    {
        try{
            $places = place::where('city_id',$data['city_id'])->get()->random(3);

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }

        return $this->apiResponse->setSuccess("Fetch Places succesfully")->setData($places);


    }
    public function addPlace($data){

        try{

            $place = new place();
            $place->name = $data['name'];
            $place->rate = $data['rate'];
            $place->city_id = $data['city_id'];

            if(!empty($data['photo'] )){
                $place->photo = $data['photo'];

            }
            $place->save();

            $newPlace = place::where('id',$place->id)->first();

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Place added succesfully")->setData($newPlace);

    }
    public function uploadphoto($data){

        try{


            $user = User::where('ApiToken',$data['ApiToken'])->first();
            // dd($user);
            $user->photo = $data['photo'];

            $user->save();


        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Photo uploaded succesfully")->setData($user);

    }
    public function editprofile($data){

        try{
            $user = User::where('ApiToken',$data['ApiToken'])->first();

            if(!empty($data['password'] )){
                $data['password'] = app('hash')->make($data['password']);
                $user->password = $data['password'];
            }
            if(!empty($data['name'] )){
                $user->name = $data['name'];
            }
            if(!empty($data['phone'] )){
                $user->phone = $data['phone'];

            }
            if(!empty($data['email'] )){
                $user->phone = $data['email'];

            }

            $user->save();


        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("User created succesfully")->setData($user);

    }
    public function forgetPassword($data)
    {
        try{
            $user = user::where('email',$data['email'])->first();
            if($user){
                $newpassword = base64_encode(str_random(8));
                $password = app('hash')->make($newpassword);
//                $hashed_random_password = Hash::make(str_random(8));
//                dd($hashed_random_password);
                $user->password =$password;
                $user->save();
//                 GeneralHelper::testSendGrid([$user,$newpassword]);

            }else{
                return $this->apiResponse->setError("Not Found Your Email , Please Try again.." , $ex)->setData();

            }

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Send your password succesfully")->setData($newpassword);

    }
    public function addCity($data)
    {
        try{
            $city = new city();
            $city->name = $data['name'];
            $city->save();

            $new_city = city::where('id',$city->id)->first();

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("City added succesfully")->setData($new_city);

    }
    public function getPhotosOfPlaces($data)
    {
        try{
            $photos = place::get('photo')->random(3);

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Get photos succesfully")->setData($photos);
    }
    public function showAPlace($data)
    {
        try{
            $place = place::where('id',$data['place_id'])->first();

        }catch(Exception $ex){
            return $this->apiResponse->setError("Missing Data" , $ex)->setData();
        }
        return $this->apiResponse->setSuccess("Get place succesfully")->setData($place);
    }
}
