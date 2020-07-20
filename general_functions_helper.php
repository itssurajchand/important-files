<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Print  an array  our customize function
 *
 */
function pr($arr, $die = false) {
    echo '<pre>';
    print_r($arr);
    if ($die)
        die;
    echo '</pre>';
}


/**
 * 
 * for base64 encoding
 */
function encode($id) {
    return base64_encode($id);
}

/**
 * for base 64 decoding 
 */
function decode($id) {
    return base64_decode($id);
}


// FUNCTIO TO CONVERT TIME ZONE 
 function convertTimeZone($dateTime,$TimeZoneFrom="America/New_York",$TimeZoneTo="UTC")
{
    // $CI =& get_instance();
    // $query="SELECT CONVERT_TZ('$dateTime','$TimeZoneFrom','$TimeZoneTo') as ConvertedDateTime";
    // return $CI->db->query($query)->row()->ConvertedDateTime;
    return $dateTime;
}


/**
 * for getting percentage
 * 
 */
function getPerCent($one, $two) {
    if ($one > 0 && $two > 0) {
        return round(number_format(($one / $two) * 100, 2, '.', ','));
    }
    return 0.00;
}

/*
 * function to detect browser
 */

function getBrowser() {
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    /*
     * First get the platform?
     */
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    } elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }

    /*
     *  Next get the name of the useragent yes seperately and for good reason
     */
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } elseif (preg_match('/Firefox/i', $u_agent)) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } elseif (preg_match('/Chrome/i', $u_agent)) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } elseif (preg_match('/Safari/i', $u_agent)) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } elseif (preg_match('/Opera/i', $u_agent)) {
        $bname = 'Opera';
        $ub = "Opera";
    } elseif (preg_match('/Netscape/i', $u_agent)) {
        $bname = 'Netscape';
        $ub = "Netscape";
    } elseif (preg_match('/Trident/i', $u_agent)) {
        $bname = 'Internet Explorer';
        $ub = "Trident";
    }

    /*
     *  finally get the correct version number
     */
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
// we have no matching number just continue
    }

// see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
//we will have two since we are not using 'other' argument yet
//see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
            $version = $matches['version'][0];
        } else {
            $version = $matches['version'][1];
        }
    } else {
        $version = $matches['version'][0];
    }

// check if we have a number
    if ($version == null || $version == "") {
        $version = "?";
    }
    if ($ub == 'MSIE' || $ub == 'Trident') {
        $ub = 'Internet Explorer';
    }

    return array(
// 'userAgent' => $u_agent,
//'name'      => $bname,
        'name' => $ub,
        'version' => $version
// 'platform'  => $platform,
// 'pattern'    => $pattern
    );
}

/**
 * get latitude and logitude using zipcode
 */
function getLntByZip($zip, $country = null) {
    $country = getcountryCodeby($country);

    $zip = str_replace(' ', '', $zip);
    $search = trim($zip . ",+" . $country);
    $url = GOOGLE_API_ZIPCODE_LOCATION_URL . $search . "&sensor=false";
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);

    $response_a = json_decode($response);
// pr($response_a, 1);
    if (isset($response_a->results[0])) {
        $lng = $response_a->results[0]->geometry->location->lng;
        $lat = $response_a->results[0]->geometry->location->lat;

        $data = array("lng" => $lng, "lat" => $lat);
    }
    if (isset($lat)) {
        return $data;
    } else {
        return array("lng" => '00.0000', "lat" => '00.0000');
    }
}

function limit_words($string, $start, $word_limit) {
    $words = explode(" ", $string);
    $word = implode(" ", array_splice($words, $start, $word_limit));
    return $word . " ...";
}



/*
 * Get age from date of birh
 */

function get_age($birthday) {
    $age = date_create($birthday)->diff(date_create('today'))->y;
    return $age;
}

/*
 * new date format
 */

function change_date_format($date = '') {
    $result = "";
    if (!empty($date)) {
        $date_split = explode(' ', $date);
        $date_one = date('F jS Y', strtotime($date_split[0]));
        $time_one = date('g:i A', strtotime($date_split[1]));
        $result = $date_one . " at " . $time_one;
    }
    return $result;
}


function mdy_date_format($date = '') {
    $result = "";
    if (!empty($date)) {
        $date_split = explode(' ', $date);
        $date_one = date('m/d/Y', strtotime($date_split[0]));
        $time_one = date('g:i A', strtotime($date_split[1]));
        $result = $date_one /*. " at " . $time_one*/;
    }
    return $result;
}

// function to send email using email templates $data is an array with $data['vEmail'] ,$data['vEmailSubject'],$data['vEmailAltBody'],$data['vEmailFrom']

function sendHtmlEmail($replaceData,$data)
{
        $CI =& get_instance(); // ci instance for executing database queriy if required.
        $replaceData['USER_IMAGE_URL']=USER_IMAGE_URL;

        ///$email_template=EMAIL_TEMPLATE_PATH.$data['vEmailBody'];
        //$email_template=file_get_contents($email_template);
        $email_template=$data['vEmailBody'];
        $vEmailBody='';
        foreach($replaceData as $key=>$replace)
        {
           $email_template=str_replace("{".$key."}",$replace,$email_template);
        }
        $vEmailBody=$email_template;

        $mail = $CI->phpmailer_library->load();

        if(!empty($data['vEmailFrom'])):
            $mail->SetFrom($data['vEmailFrom'], BRAND_NAME);
        else:
            $mail->setFrom( " ",BRAND_NAME);
        endif;

        try{
        //$mail->SetFrom = $data['vEmailFrom'];
        
        if(is_array($data['vEmail']))
        {
            foreach($data['vEmail'] as $vEmail)
            {
                $mail->addAddress($vEmail);     // Recipient Email Address
                $mail->Subject = $data['vEmailSubject'];
                $mail->Body    = $vEmailBody;
                $mail->AltBody = $data['vEmailAltBody'];
                //pr($mail,1);
                $result=$mail->send();
                if($result)
                {
                    $status=array('status'=>200);
                    return $status;
                }
            }
        }
        else
        {
            $mail->addAddress($data['vEmail']);     // Recipient Email Address
            $mail->Subject = $data['vEmailSubject'];
            $mail->Body    = $vEmailBody;
            $mail->AltBody = $data['vEmailAltBody'];
            //pr($mail,1);
            $result=$mail->send();
                if($result)
                {
                    $status=array('status'=>200);
                    return $status;
                }
        }

        } catch (Exception $e) {
                // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                $status=array('status'=>300,'error'=>$mail->ErrorInfo);
                return $status;
                //redirect('admin/login');
            }
}
