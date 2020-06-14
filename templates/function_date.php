<?php
//---------------------------------------------------------------------------------------------------------
// Functions for MSHTML
// (C)2003 Stelfair Tunisia Sarl 
//---------------------------------------------------------------------------------------------------------
define("DEFAULT_CURRENCY_SYMBOL", "$");
define("DEFAULT_MON_DECIMAL_POINT", ".");
define("DEFAULT_MON_THOUSANDS_SEP", ",");
define("DEFAULT_POSITIVE_SIGN", "");
define("DEFAULT_NEGATIVE_SIGN", "-");
define("DEFAULT_FRAC_DIGITS", 2);
define("DEFAULT_P_CS_PRECEDES", true);
define("DEFAULT_P_SEP_BY_SPACE", false);
define("DEFAULT_N_CS_PRECEDES", true);
define("DEFAULT_N_SEP_BY_SPACE", false);
define("DEFAULT_P_SIGN_POSN", 3);
define("DEFAULT_N_SIGN_POSN", 3);

// MSHTML DEFAULT_DATE_FORMAT: "yyyy/mm/dd"(default)  or "mm/dd/yyyy" or "dd/mm/yyyy"
define("DEFAULT_DATE_FORMAT", "dd/mm/yyyy");

//---------------------------------------------------------------------------------------------------------
// format a timestamp, datetime, date or time field from MySQL
// $namedformat: 0 - General Date, 1 - Long Date, 2 - Short Date, 3 - Long Time, 4 - Short Time
function FormatDateTime($ts, $namedformat)
{
    $DefDateFormat = str_replace("yyyy", "%Y", DEFAULT_DATE_FORMAT);
	$DefDateFormat = str_replace("mm", "%m", $DefDateFormat);
	$DefDateFormat = str_replace("dd", "%d", $DefDateFormat);
	
	if (is_numeric($ts)) // timestamp
	{
		switch (strlen($ts)) {
		    case 14:
		        $patt = '/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
		        break;
		    case 12:
		        $patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
		        break;
		    case 10:
		        $patt = '/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/';
		        break;
			case 8:
		        $patt = '/(\d{4})(\d{2})(\d{2})/';
		        break;
			case 6:
		        $patt = '/(\d{2})(\d{2})(\d{2})/';
		        break;
			case 4:
		        $patt = '/(\d{2})(\d{2})/';
		        break;
			case 2:
		        $patt = '/(\d{2})/';
		        break;
			default:
				return $ts;
		}		
		if ((isset($patt))&&(preg_match($patt, $ts, $matches)))
		{
			$year = $matches[1];
			$month = @$matches[2];
			$day = @$matches[3];
			$hour = @$matches[4];
			$min = @$matches[5];
			$sec = @$matches[6];
		}
		if (($namedformat==0)&&(strlen($ts)<10)) $namedformat = 2;
	}
	elseif (is_string($ts))
	{		
		if (preg_match('/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // datetime
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			$hour = $matches[4];
			$min = $matches[5];
			$sec = $matches[6];
		}
		elseif (preg_match('/(\d{4})-(\d{2})-(\d{2})/', $ts, $matches)) // date
		{
			$year = $matches[1];
			$month = $matches[2];
			$day = $matches[3];
			if ($namedformat==0) $namedformat = 2;
		}
		elseif (preg_match('/(^|\s)(\d{2}):(\d{2}):(\d{2})/', $ts, $matches)) // time
		{
			$type = "time";			
			$hour = $matches[2];
			$min = $matches[3];
			$sec = $matches[4];
			if (($namedformat==0)||($namedformat==1)) $namedformat = 3;
			if ($namedformat==2) $namedformat = 4;
		}
		else
		{
			return $ts;
		}
	}
	else
	{
		return $ts;
	}
	
	if (!isset($year)) $year = 0; // dummy value for times
	if (!isset($month)) $month = 1;
	if (!isset($day)) $day = 1;	
	if (!isset($hour)) $hour = 0;
	if (!isset($min)) $min = 0;
	if (!isset($sec)) $sec = 0;
	
	$uts = mktime($hour, $min, $sec, $month, $day, $year);
	
	if ($uts==-1) return $ts; // fail to convert
	
	switch ($namedformat) {
    case 0:
        return strftime($DefDateFormat." %p %I:%M:%S", $uts);
        break;
    case 1:
        return strftime("%A, %B %d, %Y", $uts);		
        break;
    case 2:
        return strftime($DefDateFormat, $uts);
        break;
	case 3:
        return strftime("%I:%M:%S %p", $uts);
        break;
	case 4:
        return strftime("%H:%M:%S", $uts);
        break;
	}
}

//---------------------------------------------------------------------------------------------------------
// Convert a date to MySQL format
function ConvertDateToMysqlFormat($dateStr)
{
    switch (DEFAULT_DATE_FORMAT) {
    case "yyyy/mm/dd":
        list ($year, $month, $day) = split("/", $dateStr);
        break;
    case "mm/dd/yyyy":
        list ($month, $day, $year) = split("/", $dateStr);
        break;
    case "dd/mm/yyyy":
        list ($day, $month, $year) = split("/", $dateStr);
        break;
	}
    return $year . "-" . $month . "-" . $day;
}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// FormatCurrency(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]])
//
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
//
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True 
// 0 False 
// -2 Use Default

function FormatCurrency($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits) 
{
    //Export the values returned by localeconv into the local scope
    extract(localeconv());
	
	// Set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if ($frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if ($p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if ($p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if ($n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if ($n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if ($p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if ($n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;
	
	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1) 
		$frac_digits = $NumDigitsAfterDecimal;
	
	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}
	
	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

    // Start by formatting the unsigned number
    $number = number_format(abs($amount),
                            $frac_digits,
                            $mon_decimal_point,
                            $mon_thousands_sep);
							
	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);		
	}
							
	if ($amount < 0) {
        $sign = $negative_sign;
        //The following statements "extracts" the boolean value as an integer 
        $n_cs_precedes  = intval($n_cs_precedes  == true);
        $n_sep_by_space = intval($n_sep_by_space == true);
        $key = $n_cs_precedes . $n_sep_by_space . $n_sign_posn;
    } else {
        $sign = $positive_sign;
        $p_cs_precedes  = intval($p_cs_precedes  == true);
        $p_sep_by_space = intval($p_sep_by_space == true);
        $key = $p_cs_precedes . $p_sep_by_space . $p_sign_posn;
    }
	
    $formats = array(
        // Currency symbol is after amount
        // No space between amount and sign.
        '000' => '(%s' . $currency_symbol . ')',
        '001' => $sign . '%s ' . $currency_symbol,
        '002' => '%s' . $currency_symbol . $sign,
        '003' => '%s' . $sign . $currency_symbol,
        '004' => '%s' . $sign . $currency_symbol,

        // One space between amount and sign.
        '010' => '(%s ' . $currency_symbol . ')',
        '011' => $sign . '%s ' . $currency_symbol,
        '012' => '%s ' . $currency_symbol . $sign,
        '013' => '%s ' . $sign . $currency_symbol,
        '014' => '%s ' . $sign . $currency_symbol,

        // Currency symbol is before amount
        // No space between amount and sign.
        '100' => '(' . $currency_symbol . '%s)',
        '101' => $sign . $currency_symbol . '%s',
        '102' => $currency_symbol . '%s' . $sign,
        '103' => $sign . $currency_symbol . '%s',
        '104' => $currency_symbol . $sign . '%s',

        // One space between amount and sign.
        '110' => '(' . $currency_symbol . ' %s)',
        '111' => $sign . $currency_symbol . ' %s',
        '112' => $currency_symbol . ' %s' . $sign,
        '113' => $sign . $currency_symbol . ' %s',
        '114' => $currency_symbol . ' ' . $sign . '%s');
		
    // We then lookup the key in the above array.
    return sprintf($formats[$key], $number);

}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// FormatNumber(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]])
//
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
//
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True 
// 0 False 
// -2 Use Default

function FormatNumber($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits) 
{
    //Export the values returned by localeconv into the local scope
    extract(localeconv());
	
	// Set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if ($frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if ($p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if ($p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if ($n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if ($n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if ($p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if ($n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;
	
	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1) 
		$frac_digits = $NumDigitsAfterDecimal;
	
	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}
	
	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

    // Start by formatting the unsigned number
    $number = number_format(abs($amount),
                            $frac_digits,
                            $mon_decimal_point,
                            $mon_thousands_sep);

	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);
	}

	if ($amount < 0) {
        $sign = $negative_sign;
        $key = $n_sign_posn;
    } else {
        $sign = $positive_sign;
        $key = $p_sign_posn;
    }
	
    $formats = array(
        '0' => '(%s)',
        '1' => $sign . '%s',
		'2' => $sign . '%s',
		'3' => $sign . '%s',
		'4' => $sign . '%s');
		
    // We then lookup the key in the above array.
    return sprintf($formats[$key], $number);

}

//-----------------------------------------------------------------------------------------------------------------------------------------------------------
// FormatPercent(Expression[,NumDigitsAfterDecimal [,IncludeLeadingDigit [,UseParensForNegativeNumbers [,GroupDigits]]]])
//
// NumDigitsAfterDecimal is the numeric value indicating how many places to the right of the decimal are displayed
// -1 Use Default
//
// The IncludeLeadingDigit, UseParensForNegativeNumbers, and GroupDigits arguments have the following settings:
// -1 True 
// 0 False 
// -2 Use Default

function FormatPercent($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit, $UseParensForNegativeNumbers, $GroupDigits) 
{
    //Export the values returned by localeconv into the local scope
    extract(localeconv());
	
	// Set defaults if locale is not set
	if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
	if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
	if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
	if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
	if ($frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
	if ($p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
	if ($p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
	if ($n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
	if ($n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
	if ($p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
	if ($n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;
	
	// check $NumDigitsAfterDecimal
	if ($NumDigitsAfterDecimal > -1) 
		$frac_digits = $NumDigitsAfterDecimal;
	
	// check $UseParensForNegativeNumbers
	if ($UseParensForNegativeNumbers == -1) {
		$n_sign_posn = 0;
		if ($p_sign_posn == 0) {
			if (DEFAULT_P_SIGN_POSN != 0)
				$p_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$p_sign_posn = 3;
		}
	} elseif ($UseParensForNegativeNumbers == 0) {
		if ($n_sign_posn == 0)
			if (DEFAULT_P_SIGN_POSN != 0)
				$n_sign_posn = DEFAULT_P_SIGN_POSN;
			else
				$n_sign_posn = 3;
	}
	
	// check $GroupDigits
	if ($GroupDigits == -1) {
		$mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
	} elseif ($GroupDigits == 0) {
		$mon_thousands_sep = "";
	}

    // Start by formatting the unsigned number
    $number = number_format(abs($amount)*100,
                            $frac_digits,
                            $mon_decimal_point,
                            $mon_thousands_sep);
							
	// check $IncludeLeadingDigit
	if ($IncludeLeadingDigit == 0) {
		if (substr($number, 0, 2) == "0.")
			$number = substr($number, 1, strlen($number)-1);		
	}
							
	if ($amount < 0) {
        $sign = $negative_sign;
        $key = $n_sign_posn;
    } else {
        $sign = $positive_sign;
        $key = $p_sign_posn;
    }
	
    $formats = array(
        '0' => '(%s%%)',
        '1' => $sign . '%s%%',
		'2' => $sign . '%s%%',
		'3' => $sign . '%s%%',
		'4' => $sign . '%s%%');
		
    // We then lookup the key in the above array.
    return sprintf($formats[$key], $number);

}
?>
