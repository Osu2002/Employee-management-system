<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable =['upload_date','document_name','display_to_employee'];

    // Schema::table('documents', function (Blueprint $table) {
    //     $table->boolean('display_to_employee')->default(false);
    // });
    
    

   
}
