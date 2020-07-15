<?php

/** 
 * Stripe Library for CodeIgniter 3.x 
 * 
 * Library for Stripe payment gateway. It helps to integrate Stripe payment gateway 
 * in CodeIgniter application. 
 * 
 * This library requires the Stripe PHP bindings and it should be placed in the third_party folder. 
 * It also requires Stripe API configuration file and it should be placed in the config directory. 
 * 
 * @package     CodeIgniter 
 * @category    Libraries 
 * @author      Sunny Roy 
 * @license     
 * @link       
 * @version     1.0 
 */ 


 class Stripe_lib{ 
    var $CI; 
    var $api_error; 
     
    function __construct(){ 
           $this->api_error = ''; 
           $this->CI =& get_instance(); 
           //$this->CI->load->config('stripe'); 
            
           // Include the Stripe PHP bindings library 
           require APPPATH .'third_party/stripe-php/init.php'; 
            
           // Set API key 
           \Stripe\Stripe::setApiKey(STRIPE_SECRET_TEST_KEY); 
    } 


    function addCustomer($name, $email, $token){ 
        try {
            // Add customer to stripe 
            $customer = \Stripe\Customer::create(array( 
                'name' => $name, 
                'email' => $email, 
                'source'  => $token,
                'description' => $name.' - '.$email
            )); 
            
        }
        catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$customer);
	   	}
	   	return $status; 
    }
    function createToken($card){
    	
    	try {
    		$token_result = \Stripe\Token::create(array($card));
			//return $updateCard;
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$token_result);
	   	}
	   	return $status;
    }
    function createCard($customerId, $token){
    	try{
	    	$createCard = \Stripe\Customer::createSource(
				  	$customerId,
				  	['source' => $token]
			);
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
		if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$createCard);
	   	}
	   	return $status;
    }
    function getCardByCustomerId($customerId){
    	try{
	    	$customerCard = \Stripe\Customer::retrieve(
			  $customerId
			);
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
		if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$customerCard);
	   	}
	   	return $status;
    }
    function updateCard($customerId, $cardId, $param){
    	try {
    		$updateCard = \Stripe\Customer::updateSource($customerId,$cardId,$param);
			//return $updateCard;
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'updateCard'=>$updateCard);
	   	}

	   	return $status;

    }
    function chargeCustomer($amount,$customerId,$cardId,$currency='usd',$description){
    	try{
    		$amount_cents = $amount*100;
    		$chargeArray = array(
			  "amount" => $amount_cents,
			  "currency" => $currency,
			  "source" => $cardId,
			  "description" => $description
			);
			if(!empty($customerId)):
				$chargeArray['customer'] = $customerId;
			endif;
	    	$chargeCustomer = \Stripe\Charge::create($chargeArray);
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$chargeCustomer);
	   	}

	   	return $status;

    }

    function checkToken($token){
    	try{
	    	$checkToken = \Stripe\Token::retrieve($token);
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$checkToken);
	   	}

	   	return $status;

    }
    //refund amount to user account
    function refundAmount($chargeId,$amount){
    	try{
	    	$refundAmount =  \Stripe\Refund::create([
							    'charge' => $chargeId,
							    'amount' => $amount*100,
							]);
		} 
		catch (\Stripe\Error\Card $e) {
	       // Since it's a decline, Stripe_CardError will be caught
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\RateLimit $e) {
	       // Too many requests made to the API too quickly
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\InvalidRequest $e) {
	       // Invalid parameters were supplied to Stripe's API
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Authentication $e) {
	       // Authentication with Stripe's API failed
	       // (maybe you changed API keys recently)
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\ApiConnection $e) {
	       // Network communication with Stripe failed
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (\Stripe\Error\Base $e) {
	       // Display a very generic error to the user, and maybe send
	       // yourself an email
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	} catch (Exception $e) {
	       // Something else happened, completely unrelated to Stripe
	       $body = $e->getJsonBody();
	       $err = $body['error'];
	       $error = $err['message'];
	   	}
	   	catch (Exception $e) {
	       $error = $e->getMessage();

	   	}
	   	if(!empty($error))
	   	{
	   		$status =array('status'=>false,'msg'=>$error);
	   	}
	   	else
	   	{
	   		$status=array('status'=>true,'msg'=>$refundAmount);
	   	}

	   	return $status;

    }
}