<?
class Utf8{

	public static function decode(&$input) {
		if (is_string($input)) {
			$input = utf8_decode($input);
		} else if (is_array($input)) {
			foreach ($input as &$value) {
				Utf8::decode($value);
			}

			unset($value);
		} else if (is_object($input)) {
			$vars = array_keys(get_object_vars($input));

			foreach ($vars as $var) {
				Utf8::decode($input->$var);
			}
		}
	}



	public static function encode(&$input) {
		if (is_string($input)) {
			$input = utf8_encode($input);
		} else if (is_array($input)) {
			foreach ($input as &$value) {
				Utf8::encode($value);
			}

			unset($value);
		} else if (is_object($input)) {
			$vars = array_keys(get_object_vars($input));

			foreach ($vars as $var) {
				Utf8::encode($input->$var);
			}
		}
	}

}