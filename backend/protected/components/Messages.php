<?
class Messages{
    
    private static function messagesPT() {
        return array(
            'header' => array(
                'attention' => 'Atenção'
            ),
            'generic' => array(
                'basic' => 'Não é possível continuar. Tente novamente mais tarde.'
            ),
            'user' => array(
                'dontSave' => 'Não foi possível salvar o usuário. Tente novamente mais tarde.'
            ),
            'doctor' => array(
                'dontFind' => 'Não foi possível encontrar o profissional solicitado. Tente novamente mais tarde.',
                'dontSave' => 'Não foi possível salvar o profissional. Tente novamente mais tarde.'

            ),
            'diseases' => array(
                'dontFind' => 'Não foi possível encontrar a doença solicitado. Tente novamente mais tarde.',
                'dontSave' => 'Não foi possível salvar a doença. Tente novamente mais tarde.'

            ),
            'exams' => array(
                'dontFind' => 'Não foi possível encontrar o exame solicitado. Tente novamente mais tarde.',
                'dontSave' => 'Não foi possível salvar o exame. Tente novamente mais tarde.'

            ),
            'medicine' => array(
                'dontFind' => 'Não foi possível encontrar o medicamento solicitado. Tente novamente mais tarde.',
                'dontSave' => 'Não foi possível salvar o medicamento. Tente novamente mais tarde.'

            ),
            'annotation' => array(
                'dontFind' => 'Não foi possível encontrar a anotação solicitada. Tente novamente mais tarde.',
                'dontSave' => 'Não foi possível salvar a anotação. Tente novamente mais tarde.'

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