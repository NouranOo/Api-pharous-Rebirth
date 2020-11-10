<?php

namespace App\Interfaces;

interface UserInterface
{
    public function welcome();
    public function register($data);
    public function login($data);
    public function getAllPlaces($data);
    public function addPlace($data);
    public function uploadphoto($data);
    public function editprofile($data);
    public function forgetPassword($data);
    public function getPhotosOfPlaces($data);
    public function showAPlace($data);
}
