<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gis extends Model
{
    use HasFactory;
    protected $table = 'gis';
    protected $fillable = [
        'name_marker',
        'lat',
        'long',
    ];

    public function getLokasi($id='') {
        $result = DB::table('details')->select('nama', 'alamat', 'gambar')
        ->where('id', $id)->get();
        return $result;
    }

    public function allLokasi() {
        $result = DB::table('details')->select('id', 'nama')->get();
        return $result;
    }
}
