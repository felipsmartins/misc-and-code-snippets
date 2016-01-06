<?php 


function math_log($number, $base)
{    
    $exponent = 1.0;
    $step     = 0.001; # Quanto menor o valor, maior uso de CPU.
    $result   = null;
    # okay, podemos causar "dor" a CPU com isso...
    while ($result <= $number) {        
        $result   = pow($base, $exponent);        
        $previous_exponent_value = $exponent - $step;
        $exponent += $step;
    }

    return $previous_exponent_value;
}

function digits_from_number($value)
{
	$value = (int) $value;
	$reversed_digits = array();

	while ($value > 0) {		
		$reversed_digits[] = $value % 10;
		$value = (int) ($value / 10);				
	}	

	return array_reverse($reversed_digits);
}