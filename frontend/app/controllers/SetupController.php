<?php

class SetupController extends BaseController {
    
    public function showInstallerPage() {
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
        
    }
}