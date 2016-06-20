<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;
use Redirect;
use Session;
use Input;
use Form;
use View;

class ImageController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('image.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'width' => ['integer', 'between:1,9999'],
            'height' => ['integer', 'between:1,9999'],
            'image' => ['required', 'image', 'max:10000']
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::to('image/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            // Image Upload
            if (Input::hasfile('image')) {
        
                $extension = Input::file('image')->getClientOriginalExtension(); // Get image extension
                $now = new \DateTime(); // Get date and time
                $date = $now->getTimestamp(); // Convert date and time in timestamp
                $fileName = $date . '.' . $extension; // Give name to image
                $destinationPath = 'images'; // Define destination path
                $img = Input::file('image')->move($destinationPath, $fileName); // Upload image to destination path
                $image_path = $destinationPath . '/' . $fileName; // Write image path in DB
                
                if (Input::has('width') && Input::has('height')) {
                    $new_width = Input::get('width');
                    $new_height = Input::get('height');
                    $new_ratio = $new_width/$new_height;

                    // Resize image 
                    $size = getimagesize($image_path); // Get image size
                    $original_width = $size[0];
                    $original_height = $size[1];
                    $original_ratio = $original_width/$original_height; // Get original ratio width/height
					
                    if ($original_ratio > $new_ratio) { // If ratio is greater than optimal (900px/300px)
                        $width = $original_width/($original_height/$new_height);
                        $height = $new_height;
                        $shift_x = (($width - $new_width)*($original_width/$width))/2;
                        $shift_y = 0;
                    } elseif ($original_ratio < $new_ratio) { // If ratio is less than optimal (900px/300px)
                        $width = $new_width;
                        $height = $original_height/($original_width/$new_width);
                        $shift_x = 0;
                        $shift_y = (($height - $new_height)*($original_height/$height))/2; //should be equal to 330 or 220
                    } else { // If ratio is already optimal (900px/300px = 3)
                        $width = $new_width;
                        $height = $new_height;
                        $shift_x = 0; // No need to crop horizontally
                        $shift_y = 0; // No need to crop vertically
                    }

                    $src = imagecreatefromstring(file_get_contents($image_path));

                    // And erase the original image
                    // With crop
                    $dst = imagecreatetruecolor($new_width,$new_height);
                    //imagecopyresampled($dst, $src, 0, 0, $shift_x, $shift_y, $new_width, $new_height, $size[0], $size[1]);
                    imagecopyresampled($dst, $src, 0, 0, $shift_x, $shift_y, $width, $height, $original_width, $original_height);
                    imagedestroy($src); // Free up memory
                    imagejpeg($dst, $image_path, 100); // adjust format as needed
                    imagedestroy($dst);               
                }

            } // end Input::hasfile('image')

			$web_root = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']); 
			
            return Redirect::route('image.create')->with('success', 'Your image has been created! It is located in the <b>public/images</b> folder. Click <a href="' . $web_root .'/' . $image_path . '">here</a> to view it.');

        } //end if
    }
}
