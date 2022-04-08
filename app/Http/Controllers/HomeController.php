<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gis;

class HomeController extends Controller
{

    public function __construct() {
        $this->Gis = new Gis();
    }
    public function index() {
        $datas = $this->Gis->allLokasi();
        // dd($datas);
        return view('home', compact('datas'));

    }

    public function dataGeo() {
        $datas = Gis::get();
        return json_encode($datas);
    }

    public function detail($id='') {
        $result = $this->Gis->getLokasi($id);
        return json_encode($result);
    }
}
 