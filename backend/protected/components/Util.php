<?
class Util{
	
	/*
	//Exemplos de formatação de data
	$data_teste = '28/06/1987 23:22:00';
	echo "data: ".Util::formataDataHora($data_teste,'dd/MM/yyyy HH:mm:ss','d/m/Y H:i');
	echo "<br/>data_app: ".Util::formataDataApp('1987-06-28');
	echo "<br/>data_hora_app: ".Util::formataDataHoraApp('1987-06-28 23:22:21');
	echo "<br/>data_bd: ".Util::formataDataBanco('28/06/1987');
	echo "<br/>data_hora_bd: ".Util::formataDataHoraBanco('28/06/1987 23:22:21');
	*/
	
	public static function formataDataBanco($data){
		return Util::formataDataHora($data,Yii::app()->locale->getDateFormat(),'Y-m-d');
	}
	
	public static function formataDataHoraBanco($data){
		return Util::formataDataHora($data,Yii::app()->locale->getDateFormat().' '.Yii::app()->locale->getTimeFormat(),'Y-m-d H:i:s');	
	}
	
	public static function formataDataHoraApp($data){
		return Util::formataDataHora($data,'yyyy-MM-dd HH:mm:ss','d/m/Y H:i:s');
	}
	
	public static function formataDataApp($data){
		return Util::formataDataHora($data,'yyyy-MM-dd','d/m/Y');
	}
	
	public static function resumeData($data){
		return substr($data,0,11);
	}
	
	public static function formataDataHora($data,$formato_entrada,$formato_saida){
		$tamanho_string = strlen($formato_entrada);
		$data = substr($data,0,$tamanho_string);
		$data = str_pad($data,$tamanho_string,':00');
		return date($formato_saida,CDateTimeParser::parse($data,$formato_entrada));	
	}
	
	public static function formataTexto($texto_original = ""){
		
		if(empty($texto_original))
			return $texto_original;
		
		$texto = htmlentities($texto_original,ENT_COMPAT,'ISO-8859-1');
		
		$array_encontrar = array(
			"\n",
			"–",
		);
		$array_substituir = array(
			"<br/>",
			"-",
		);
		
		$texto = str_replace($array_encontrar,$array_substituir,$texto);
		
		return $texto;
	}
	
	public static function formataResumo($textoInteiro,$tamanho){
		 if (strlen($textoInteiro)>$tamanho+25){
		  $posicao = strpos($textoInteiro ," ", $tamanho);
		  $textoParcial = strip_tags(substr ($textoInteiro, 0, $posicao)); //Pega o fragmento e elimina todas as tags html, caso existam.
		  $textoParcial .= "...";
		 }
		 else{
		  $textoParcial = strip_tags($textoInteiro);
		 }
		 return $textoParcial;
	}
	
	public static function file_encode($var) {

		$var = strtolower($var);
		
		$var = ereg_replace("[áàâãª]","a",$var);	
		$var = ereg_replace("[éèê]","e",$var);	
		$var = ereg_replace("[íìî]","i",$var);	
		$var = ereg_replace("[óòôõº]","o",$var);	
		$var = ereg_replace("[úùû]","u",$var);	
		$var = ereg_replace("[+]","",$var);	
		$var = str_replace("ç","c",$var);
		$var = str_replace(" ","_",$var);
		
		
		return $var;
	}
	
	public static function file_encode_no_space($var) {

		$var = strtolower($var);
		
		$var = ereg_replace("[áàâãª]","a",$var);	
		$var = ereg_replace("[éèê]","e",$var);	
		$var = ereg_replace("[íìî]","i",$var);	
		$var = ereg_replace("[óòôõº]","o",$var);	
		$var = ereg_replace("[úùû]","u",$var);	
		$var = ereg_replace("[+]","",$var);	
		$var = str_replace("ç","c",$var);
		$var = str_replace(" ","",$var);
		
		
		return $var;
	}

	public static function file_encode_yes_space($var) {

		
		$var = ereg_replace("[áàâãª]","a",$var);	
		$var = ereg_replace("[éèê]","e",$var);	
		$var = ereg_replace("[íìî]","i",$var);	
		$var = ereg_replace("[óòôõº]","o",$var);	
		$var = ereg_replace("[úùû]","u",$var);	
		$var = ereg_replace("[+]","",$var);	
		$var = str_replace("ç","c",$var);
		$var = str_replace(" "," ",$var);
		
		
		return $var;
	}
        
        
	public static function soNumero($str) {
		return preg_replace("/[^0-9]/", "", $str);
	}
	
	public static function soLetras($str) {
		return preg_replace("/[^a-zA-Z\s]/", "", $str);
	}
        
	public static function formataMoedaFloat($valor) {
		return str_replace(",",".",(str_replace(".","",$valor)));
	}
	
	public static function formataFloatMoeda($valor) {
		return number_format($valor,2,',','.');
	}
	
	public static function formatErrors($model,$concat = '<br/>'){
		$erros_texto = "";
		if(count($erros = $model->getErrors()) > 0){
			foreach($erros as $erro){
				if(is_array($erro)){
					foreach($erro as $err){
						$erros_texto .= Util::formataTexto($err).$concat;
					}
				}
				else
					$erros_texto .= Util::formataTexto($erro).$concat;
			}
		}
		return $erros_texto;
	}

	public function monthOfTheYear() {
		return array(
			'1' => 'Janeiro',
			'2' => 'Fevereiro',
			'3' => 'Março',
			'4' => 'Abril',
			'5' => 'Maio',
			'6' => 'Junho',
			'7' => 'Julho',
			'8' => 'Agosto',
			'9' => 'Setembro',
			'10' => 'Outubro',
			'11' => 'Novembro',
			'12' => 'Dezembro'
		);
	}

    public static function debugArray($array,$exit=false){
        echo '<pre>'.print_r($array,true).'</pre>';
        if($exit)
            exit();
    }
	
    public static function removerAcentos($string) {
		$string = strtolower($string);
		$string = rtrim($string);
		$string = ltrim($string);
		$string = preg_replace("/[áàâãä]/", "a", $string);
		$string = preg_replace("/[ªº]/", "", $string);
		$string = preg_replace("/[ÁÀÂÃÄÄ]/", "A", $string);
		$string = preg_replace("/[éèêëë]/", "e", $string);
		$string = preg_replace("/[ÉÈÊË]/", "E", $string);
		$string = preg_replace("/[íìï]/", "i", $string);
		$string = preg_replace("/[ÍÌÏ]/", "I", $string);
		$string = preg_replace("/[óòôõö]/", "o", $string);
		$string = preg_replace("/[ÓÒÔÕÖ]/", "O", $string);
		$string = preg_replace("/[úùü]/", "u", $string);
		$string = preg_replace("/[ÚÙÜ]/", "U", $string);
		$string = preg_replace("/ç/", "c", $string);
		$string = preg_replace("/Ç/", "C", $string);
		$string = preg_replace("/[][><}{)(:;,!?*%~^`&#@]/", "", $string);
        $string = preg_replace("/ /", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace("--", "-", $string);
        $string = str_replace(".", "", $string);
        $string = str_replace("´", "", $string);
        $string = str_replace('"', "", $string);

        return $string;
    }
}