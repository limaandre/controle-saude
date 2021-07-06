<?
class Messages{
    
    private static function messagesPT() {
        return array(
            'header' => array(
                'attention' => 'Aten��o'
            ),
            'generic' => array(
                'basic' => 'N�o � poss�vel continuar. Tente novamente mais tarde.'
            ),
            'user' => array(
                'dontSave' => 'N�o foi poss�vel salvar o usu�rio. Tente novamente mais tarde.'
            ),
            'doctor' => array(
                'dontFind' => 'N�o foi poss�vel encontrar o profissional solicitado. Tente novamente mais tarde.',
                'dontSave' => 'N�o foi poss�vel salvar o profissional. Tente novamente mais tarde.'

            ),
            'diseases' => array(
                'dontFind' => 'N�o foi poss�vel encontrar a doen�a solicitado. Tente novamente mais tarde.',
                'dontSave' => 'N�o foi poss�vel salvar a doen�a. Tente novamente mais tarde.'

            ),
            'exams' => array(
                'dontFind' => 'N�o foi poss�vel encontrar o exame solicitado. Tente novamente mais tarde.',
                'dontSave' => 'N�o foi poss�vel salvar o exame. Tente novamente mais tarde.'

            ),
            'medicine' => array(
                'dontFind' => 'N�o foi poss�vel encontrar o medicamento solicitado. Tente novamente mais tarde.',
                'dontSave' => 'N�o foi poss�vel salvar o medicamento. Tente novamente mais tarde.'

            ),
            'annotation' => array(
                'dontFind' => 'N�o foi poss�vel encontrar a anota��o solicitada. Tente novamente mais tarde.',
                'dontSave' => 'N�o foi poss�vel salvar a anota��o. Tente novamente mais tarde.'

            )
        );
    } 
    
    private static function messagesEN() {
        return array(
            'header' => array(
                'attention' => 'Attention'
            ),
            'generic' => array(
                'basic' => 'It is not possible to continue. Try again later.'
            ),
            'user' => array(
                'dontSave' => 'The user could not be saved. Try again later.'
            ),
            'doctor' => array(
                'dontFind' => 'The requested professional could not be found. Try again later.',
                'dontSave' => 'The professional could not be saved. Try again later.'
            ),
            'diseases' => array(
                'dontFind' => 'The requested diseases could not be found. Try again later.',
                'dontSave' => 'The diseases could not be saved. Try again later.'
            ),
            'exams' => array(
                'dontFind' => 'The requested exam could not be found. Try again later.',
                'dontSave' => 'The exam could not be saved. Try again later.'
            ),
            'medicine' => array(
                'dontFind' => 'The requested medication could not be found. Try again later.',
                'dontSave' => 'The medication could not be saved. Try again later.'
            ),
            'annotation' => array(
                'dontFind' => 'The requested annotation could not be found. Try again later.',
                'dontSave' => 'The annotation could not be saved. Try again later.'
            )
        );
    }

    public static function returnMsg($languages, $type_index, $type_msg) {
        $msg = Messages::messagesEN();
        if ($languages === 'pt') {
            $msg = Messages::messagesPT();
        }
        return $msg[$type_index][$type_msg];
    }
}