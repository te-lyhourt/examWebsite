<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

use App\Models\Answers;
use Illuminate\Http\Request;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use ZipArchive;

class answerController extends Controller
{
        //create new answer
        public function store(Request $request)
        {
            // Validation rules
            $validator = $request->validate([
                'questions_id' => 'required|exists:questions,id',
                'user_id' => 'required|exists:users,id',
            ]);
            //check if file
            if($request->file()){
                //deal with file
                $username = strstr($request->user_email, '@', true);
                $path = $request->project_id ."/".$username."/questions_".$request->questions_id."_".$request->file('file')->getClientOriginalName();
                Gdrive::put($path,$request->file('file'));
                Answers::create([
                    'questions_id' => $validator['questions_id'],
                    'user_id' => $validator['user_id'],
                    'answer'=>$request->answer,
                    'filePath'=>$path,
                    'fileName'=>$request->file('file')->getClientOriginalName(),
                ]);
            }
            else{
                // create group
                Answers::create([
                    'questions_id' => $validator['questions_id'],
                    'user_id' => $validator['user_id'],
                    'answer'=>$request->answer,
                ]);
            }
        }
        public function update(Request $request,$id)
        {
            $answers = Answers::find($id);
            if($request->updateFile){
                $username = strstr($request->user_email, '@', true);
                $path = $request->project_id ."/".$username."/questions_id_".$request->questions_id.$request->file('file')->getClientOriginalName();
                Gdrive::put($path,$request->file('file'));
                Gdrive::delete($request->oldPath);                    
                $answers->filePath=$path;
                $answers->fileName=$request->file('file')->getClientOriginalName();
            }
            
            $answers->answer = $request->answer;
            
            $answers->save();
        }
        public function export(Request $request, $id)
        {
            //list all folder 
            $list = Gdrive::all($id, true);

            // dd( $list);

            // Create a new ZipArchive instance
            $zip = new ZipArchive();

            // Create a temporary file in memory to store the zip content
            $tempFile = tmpfile();
            $tempFilePath = stream_get_meta_data($tempFile)['uri'];

            // Open the zip file for writing
            if ($zip->open($tempFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                // Loop through each item in the list
                foreach ($list as $item) {
                    // If it's a directory, add an empty directory to the zip file
                    if ($item->type()== "dir" ) {
                        $zip->addEmptyDir($item->path());
                    } 
                    // If it's a file, add the file to the zip file
                    elseif ($item->type()=="file") {
                        // Get the contents of the file
                        $fileContents = Gdrive::get($item->path());
                        // Add the file to the zip file with its relative path
                        $zip->addFromString($item->path(), $fileContents->file);
                    }
                }
                
                // Close the zip file
                $zip->close();

                // Set headers for the HTTP response
                $headers = [
                    'Content-Type' => 'application/zip',
                    'Content-Disposition' => 'attachment; filename="download.zip"'
                ];
                // Open the temporary file for reading
                $tempFileHandle = fopen($tempFilePath, 'rb');

                // Read the content of the temporary file into a string variable
                $tempFileContent = stream_get_contents($tempFileHandle);

                // Close the temporary file handle
                fclose($tempFileHandle);

                // Create a new response with the zip file content
                $response = new Response($tempFileContent, 200, $headers);

                // Delete the temporary file after it's streamed
                register_shutdown_function(function () use ($tempFile) {
                    fclose($tempFile);
                });

                return $response;
            }
        }
}