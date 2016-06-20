<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
            //'title' => ['required', 'min:3'],
            //'slug'  => ['required', 'min:2', 'alpha_dash'],
            'width' => ['integer', 'between:1,9999'],
            'height' => ['integer', 'between:1,9999'],
            'image' => ['required', 'image', 'max:10000']
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            //return Redirect::route('home');
            return Redirect::to('image/create')
                ->withErrors($validator)
                ->withInput();
        } else {
            /*$post = new Post;

            $post->user_id = Sentinel::getUser()->id;
            $post->title = Input::get('title');
            $post->slug = Input::get('slug');
            $post->content_raw = Input::get('text');
            $post->content_html = MarkdownExtra::defaultTransform(Input::get('text'));*/

            // Image Upload
            if (Input::hasfile('image')) {
        
                $extension = Input::file('image')->getClientOriginalExtension(); // Get image extension
                $now = new \DateTime(); // Get date and time
                $date = $now->getTimestamp(); // Convert date and time in timestamp
                $fileName = $date . '.' . $extension; // Give name to image
                $destinationPath = 'images'; // Define destination path
                $img = Input::file('image')->move($destinationPath, $fileName); // Upload image to destination path
                $image_path = $destinationPath . '/' . $fileName; // Write image path in DB
                //$post->image_path = $new_path;
                
                if (Input::has('width') && Input::has('height')) {
                    $new_width = Input::get('width');
                    $new_height = Input::get('height');
                    $new_ratio = $new_width/$new_height;
                    // Resize image
                    //$filename = $new_path; // Get image

                    // Resize image to format 900px/300px
                    $size = getimagesize($image_path); // Get image size
                    //dd($size);
                    $old_width = $size[0];
                    $old_height = $size[1];

                    $old_ratio = $old_width/$old_height; // Get ratio width/height
                    //dd($ratio);
                    if ($old_ratio > $new_ratio) { // If ratio is greater than optimal (900px/300px)
                        $width = $old_width/($old_height/$new_height);
                        $height = $new_height;
                        $shift_x = (($width - $new_width)*($old_width/$width))/2;
                        //dd($shift_x);
                        $shift_y = 0;
                        //dd($width);
                    } elseif ($old_ratio < $new_ratio) { // If ratio is less than optimal (900px/300px)
                        $width = $new_width;
                        $height = $old_height/($old_width/$new_width);
                        $shift_x = 0;
                        $shift_y = (($height - $new_height)*($old_height/$height))/2; //should be equal to 330 or 220
                        //$shift_y = ($height - $new_height)/2;
                        //dd($shift_y);
                        //dd($height);
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
                    imagecopyresampled($dst, $src, 0, 0, $shift_x, $shift_y, $width, $height, $old_width, $old_height);
                    imagedestroy($src); // Free up memory
                    imagejpeg($dst, $image_path, 100); // adjust format as needed
                    imagedestroy($dst);               
                }

            } // end Input::hasfile('image')

  

            /*$tags = Input::get('tags');
            //dd($tags);

            $post->save();

            //Post::create($input);
            if ($post->save()) {
                $post_id = $post->id;

                $post = Post::find($post_id);
                if (Input::has('tags')) {   
                    foreach ($tags as $tag) {     
                        $post->tags()->attach($tag);
                    }
                }
            }*/
            return Redirect::route('image.create')->with('success', 'New image created! Check out the <b>public/images</b> folder.');

        } //end if
    }
}
