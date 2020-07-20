<?php

function _getDislpayDate($date, $format) {
    return date($format, strtotime($date));
}

function convert24Hrs($time) {
    //$time = str_replace(' ', '', strtoupper($time));
    // $time = str_replace(':AM', ' AM', $time);
    //$time = str_replace(':PM', ' PM', $time);

    return date("H:i:s", strtotime($time));
}

function convert12Hrs($time) {
    $time = strtoupper(date("h:i a", strtotime($time)));
    return $time;
}

function currentDate() {
    // return date("Y-m-d H:i:s", strtotime('+1 hour'));
    return date("Y-m-d H:i:s");
}

/////////////// Date Format in numeric /////////////////////
//function dateFormat($date, $time = null) {
//    $date = strtotime($date);
//    if ($time == 'false') {
//        $date = date("m-d-Y", $date);
//    } else {
//        $date = date("m-d-Y h:i A", $date);
//    }
//
//    return $date;
//}
/////////////// Date Format /////////////////////
function dateFormat($date, $format = "d-m-Y h:i A") {
    $date = strtotime($date);
    $date = date($format, $date);
    return $date;
}

/////////////// Date Format According to month name/////////////////////
function dateFormatMDY($date, $time = true) {
    $date = strtotime($date);
    if ($time == false) {
        $date = date("M d, Y", $date);
    } else {
        $date = date("M d, Y h:i A", $date);
    }

    return $date;
}

// function to get week start and end date of week by given date
function x_week_range($date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    return array(date('Y-m-d', $start),
                 date('Y-m-d', strtotime('next saturday', $start)));
}

function daysRemaining($currentDate, $end, $out_in_array = false) {

    $intervalo = date_diff(date_create($currentDate), date_create($end));
    $out = $intervalo->format("Years:%Y,Months:%M,Days:%d,Hours:%H,Minutes:%i,Seconds:%s");
    if (!$out_in_array)
        return $out;
    $a_out = array();
    @array_walk((explode(',', $out)), function($val, $key) use(&$a_out) {
        $v = explode(':', $val);
        $a_out[$v[0]] = $v[1];
    });
    return $a_out;
}


// time find how much time ago from current time and past time

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) .'':'just now'; //' ago' : 'just now';
}

/**
 * 
 * @param type $first
 * @param type $last
 * @param type $step
 * @param type $format
 * @return type
 */
function dateRange($first, $last, $step = '+1 day', $format = 'd-m-Y') {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {
        $dates[] = date($format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}

/**
 * function to get timezone
 */
function getTimeZoneByUserId($user_id) {
    load_class(array('users'));
    $obj_users = new users();
    $row = $obj_users->db->rawQuery("select t.abbrevation from users as u left join timezone as t on(u.time_zone=t.id) where u.user_id=$user_id");
    return $row[0]['abbrevation'];
}

/**
 * function to get timezone name
 */
function getTimeZoneNameByUserId($user_id) {
    load_class(array('users'));
    $obj_users = new users();
    $row = $obj_users->db->rawQuery("select t.name from users as u left join timezone as t on(u.time_zone=t.id) where u.user_id=$user_id");
    return $row[0]['name'];
}

/*
 * Get Timezone detail
 */

function getTimeZoneDetail($user_id) {
    load_class(array('users'));
    $obj_users = new users();
    $row = $obj_users->db->rawQuery("select t.* from users as u left join timezone as t on(u.time_zone=t.id) where u.user_id=$user_id");
    return $row[0];
}

/**
 * Convert a date(time) string to another format or timezone
 *
 * DateTime::createFromFormat requires PHP >= 5.3
 *
 * @param string $dt
 * @param string $tz1
 * @param string $df1
 * @param string $tz2
 * @param string $df2
 * @return string
 */
function date_convert($dt, $tz1, $df1, $tz2, $df2) {
    $res = '';
    if (!in_array($tz1, timezone_identifiers_list())) { // check source timezone
        trigger_error(__FUNCTION__ . ': Invalid source timezone ' . $tz1, E_USER_ERROR);
    } elseif (!in_array($tz2, timezone_identifiers_list())) { // check destination timezone
        trigger_error(__FUNCTION__ . ': Invalid destination timezone ' . $tz2, E_USER_ERROR);
    } else {
        // create DateTime object
        $d = DateTime::createFromFormat($df1, $dt, new DateTimeZone($tz1));
        // check source datetime
        if ($d && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0) {
            // convert timezone
            $d->setTimeZone(new DateTimeZone($tz2));
            // convert dateformat
            $res = $d->format($df2);
        } else {
            trigger_error(__FUNCTION__ . ': Invalid source datetime ' . $dt . ', ' . $df1, E_USER_ERROR);
        }
    }
    return $res;
}

/*
 *  Get user current datetime
 */

function getUserCurrentDateTime($timezone, $format = 'Y-m-d H:i:s') {
    return date_convert(gmdate('Y-m-d H:i:s'), 'UTC', 'Y-m-d H:i:s', $timezone, $format);
}

/*
 * Get current week day range  
 */

function rangeWeek($date, $format = 'Y-m-d') {
    $date = strtotime($date);
    //return  date('N', $date);

    if (date('w', $date) == 0) {
        $start_date = date('Y-m-d', $date);
        $end_date = date('Y-m-d', strtotime('+ 6 days', $date));
    } else if (date('w', $date) == 6) {
        $start_date = date('Y-m-d', strtotime('- 6 days', $date));
        $end_date = date('Y-m-d', $date);
    } else {
        $start_date = date('Y-m-d', strtotime('last sunday', $date));
        $end_date = date('Y-m-d', strtotime('next saturday', $date));
    }

    $days = array();
    $ranges = dateRange($start_date, $end_date, "+1 day");
    foreach ($ranges as $range) {
        $key = date("w", strtotime($range));
        $days[$key] = date($format, strtotime($range));
    }
    return $days;
}

/**
 * function to get timezone
 */
function getTimeZoneByTimeZoneId($id) {
    load_class(array('users'));
    $obj_users = new users();
    $row = $obj_users->db->rawQuery("select abbrevation from timezone where id = '$id'");
    return $row[0]['abbrevation'];
}

function halfHourTimes( $lower = 0, $upper = 86400, $step = 1800, $format = '' ) {
    $times = array();

    if ( empty( $format ) ) {
        $format = 'g:i a';
    }

    foreach ( range( $lower, $upper, $step ) as $increment ) {
        $increment = gmdate( 'H:i', $increment );

        list( $hour, $minutes ) = explode( ':', $increment );

        $date = new DateTime( $hour . ':' . $minutes );

        $times[(string) $increment] = $date->format( $format );
    }

    return $times;
}