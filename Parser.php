<?php

namespace ACWPD\Helpers;

/* 
 * Provides parsing tools for ACWPD Projects
 * Feel free to use this in your projects! Just provide Attribution by keeping this block in place!
 * 
 * This is version 1.1
 * 
 * For the latest version, please visit: https://github.com/farfromunique/ACWPD_Tools
 * 
 * This code is copyright (C) 2015 Aaron Coquet / ACWPD
 */ 
 
class Parser {
    /** @param string $str String containing a time in UTC surrounded by ":-:UTC:-:" on one side and ":-:" on the other.
     * @param mixed $TimeZoneOffset The difference from a given timezone.
     * @param string $timeFormat The PHP-style time format desired by the user.
     * @return string The string returned either has the time adjusted by the offset given, or (on error) the original text.
     */
	public function fixUTC($str,$TimeZoneOffset,$timeFormat) {
		$bits = explode(":-:",$str);
		for ($i=0;$i<count($bits);$i++) {
			if ($bits[$i] == "UTC") {
				$tds = explode(' ',$bits[$i + 1]); // Format is (2013-08-14 05:00:00)
				$tds_date = explode('-',$tds[0]);
				$tds_time = explode(':',$tds[1]);
				$fixTime = date($timeFormat,mktime($tds_time[0],$tds_time[1],$tds_time[2],$tds_date[1],$tds_date[2],$tds_date[0]) + $TimeZoneOffset);
				$bits[$i] = '';
				$bits[$i+1] = $fixTime;
				$output = implode($bits);
				return $output;
			}
		}
		return $str;
	}

	public function makeMainDiv($str) {
		return "<div id='content' class='content'>$str</div>";
	}

    public function buildOutput($pageName) {
        ob_start();
        require_once($pageName);
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    public function getParameters($uri) {
        $uri = strtolower($uri);
        $request  = str_replace("", "", $uri);
        if ($request == '/') {
            return array($request);
        } else {
            $site_array  = explode("/", $request);

            foreach($site_array as $key => $value) {
                if($value == "") {
                    unset($site_array[$key]);
                }
            }
            return array_values($site_array);
        }
    }

    public function getRandomFromArray($arr) {
        $keys = array_keys($arr);
        shuffle($keys);
        return $arr[$keys[0]];
    }

    public function parseURI(string $URI): array {
        $out = [];
        $matches = [];
        $patterns = [
            'scheme' => '/^(\w+):\/\//',
            'user' => '\/\/(\w+)[:@]',
            'pass' => ':(\w+)@',
            'host' => '(?:(?::\/\/)|@)([\w\.]+)(?:\/|$)',
            'path' => '\w(\/(?:\w)+|\/)',
            'query' => '\w\?((?:\w|\&|=)+)[#\/!]?\w*$',
            'fragment' => '#((?:\w)+)'
        ];
        foreach ($patterns as $name => $pattern) {
            \preg_match($pattern,$URI,$matches);
            if( isset($matches[1])) {
                $out[$name] = $matches[1];
            } else {
                $out[$name] = '';
            }
        }
        return $out;
    }
}

?>