<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbApplication extends Model
{
    protected $fillable = ['nomor_pendaftaran', 'nama_lengkap', 'nisn', 'nama_wali', 'email', 'telepon', 'unit', 'kelas_tujuan', 'alamat', 'status', 'catatan'];
}