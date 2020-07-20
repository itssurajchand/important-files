<?php
   
require APPPATH . 'libraries/REST_Controller.php';
     
class ApiController extends REST_Controller {
    
	  /**
     * Get All Data from this method.
     *
     * @return Response
    */
    public function __construct() {
       parent::__construct();
       //$this->load->database();
    }
       
    /**
     * Get All Data from this method.
     *
     * @return Response
    */
	public function index_get($id = 0)
	{
        if(!empty($id)){
            $data = array('abc'=>'Suraj','cde'=>'Chand');
        }else{
            $data = array('abc'=>'Suraj','cde'=>'Chand');
        }
     
        $this->response($data, REST_Controller::HTTP_OK);
	}  	
}