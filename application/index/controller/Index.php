<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
       return dirname($_SERVER['SCRIPT_NAME']);
    }
}
