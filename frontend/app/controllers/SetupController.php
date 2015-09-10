<?php
class SetupController extends BaseController {
    
    public function showInstallerPage() {
        return View::make("setup/installer-new");
    }
    
    public function finishSetup() {
        File::delete(storage_path()."/setup");
        if(!File::exists(storage_path()."/setup")) {
            $response = PaperworkHelpers::STATUS_SUCCESS;
        }else{
            $response = PaperworkHelpers::STATUS_ERROR;
        }
        return PaperworkHelpers::apiResponse($response, array());
    }
    
    public function setupDatabase() {
        // Create credentials string
        $string = Input::get("driver") . ", " . Input::get("server") . ", " . Input::get("port") . ", " . Input::get("username") . ", " . Input::get("password");
        // Create file to hold info
        // Save File
        //File::delete(storage_path()."/db_settings");
        File::put(storage_path()."/db_settings", $string);
        //File::put(storage_path()."/db_settings", "");
        //chmod(0777, storage_path()."/db_settings");
        //exec("chmod 777 ".storage_path()."/db_settings");
        // Open connection
        // Check if any errors occurred
        // If true, send error response 
        // If false, send success response 
        if(DB::connection()->getDatabaseName()) {
            $response = PaperworkHelpers::STATUS_SUCCESS;
            define('STDIN',fopen("php://stdin","r"));
            Artisan::call("migrate", ['--quiet' => true, '--force' => true]);
        }else{
            $response = PaperworkHelpers::STATUS_NOTFOUND;
            //File::delete(storage_path()."/db_settings");
            unlink(storage_path()."/db_settings");
        }
        //DB::disconnect();
        
        return PaperworkHelpers::apiResponse($response, array());
    }
    
/*    public function showInstallerPage() {
        return View::make('setup/installer');
    }
    
    public function setupDatabase() {
        if(DB::connection()) {
            $response = PaperworkHelpers::STATUS_SUCCESS;
        }else{
            $response = PaperworkHelpers::STATUS_NOTFOUND;
        }
        return PaperworkHelpers::apiResponse($response, array());
    }
    
    public function finishSetup() {
        $filename = public_path()."/setup";
        //File::delete($filename);
        chmod(public_path(), 0777);
        unlink($filename);
        chmod(public_path(), 0755);
        if(!File::exists($filename)) {
            $response = PaperworkHelpers::STATUS_SUCCESS;
        }else{
            $response = PaperworkHelpers::STATUS_ERROR;
        }
        return PaperworkHelpers::apiResponse($response, array());
    }
    
    public function registerAdmin() {
        
    }*/
}