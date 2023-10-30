<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\CropImage;
use Illuminate\Support\Facades\DB;
 
class CropImageController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('crop-image-upload');
    }
    
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function cropImageUploadAjax(Request $request)
    {
        $folderPath = public_path('upload/');
 
        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
 
        $imageName = 'user-'. $request->userId . '-profile.png';
 
        $imageFullPath = $folderPath.$imageName;
 
        file_put_contents($imageFullPath, $image_base64);
 
        //  $saveFile = new CropImage;
        //  $saveFile->name = $imageName;
        //  $saveFile->save();

        $userDataSql = "UPDATE tbl_user
            SET profile = '$imageName'
            WHERE id = $request->userId";
    
            // Execute the raw SQL query to update the record
        $userData = DB::update($userDataSql);
    
        return response()->json(['success'=>'Crop Image Uploaded Successfully']);
    }
}