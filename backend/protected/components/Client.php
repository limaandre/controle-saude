<?
class Client{

	private static function minhaSaude(){
		return array('g5dKMw2w4KZapcHujSO4RyPwufp0lY3HTWSgHKSBWrcBKjwu3B');
	}

	public static function allClients() {
		$clients = array(
			'minha-saude'
		);
		return $clients;
	}
	
	public static function tokenClient($client) {
		$clients = array(
			'minha-saude' => Client::minhaSaude()
		);

		if ($client) {
			return isset($clients[$client]) ? $clients[$client] : array();
		}
		return $clients;
	}
}