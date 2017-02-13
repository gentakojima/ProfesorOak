<?php

class Group extends TelegramApp\Module {
	protected $runCommands = FALSE;

	public function run(){
		if(!$this->chat->is_group()){ return; }
		if($this->user->step != NULL){ $this->step(); }
		parent::run();
	}

	protected function step(){
		$step = $this->user->step;
		if($step == "RULES" or $step == "WELCOME"){
			if(!$this->chat->is_admin($this->user->id)){
				$this->user->step = NULL;
				$this->end();
			}

			$text = $this->telegram->text_encoded();
			if(strlen($text) < 4){ $this->end(); }
			if(strlen($text) > 4000){
				$this->telegram->send
					->text("Buah, demasiado texto! Relájate un poco anda ;)")
				->send();
				$this->end();
			}
			// $this->analytics->event('Telegram', 'Set rules');
			// $this->analytics->event('Telegram', 'Set welcome');

			$this->chat->settings[strtolower($step)] = $text;
			$this->user->step = NULL;

			$this->telegram->send
				->text("Hecho!")
			->send();
			$this->end();
		}
	}

	protected function hooks(){
		if(
			$this->telegram->text_command("autokick") or
			$this->telegram->text_command("adios") or
			$this->telegram->text_has("bomba de humo")
		){
			$this->autokick();
			$this->end();
		}

		elseif(
			( $this->telegram->text_has("lista") and $this->telegram->text_has(["admins", "admin", "administradores"]) and $this->telegram->words() <= 8 ) or
			( $this->telegram->text_command("adminlist") or $this->telegram->text_command("admins") )
		){
			$this->adminlist();
			$this->end();
		}

		elseif(
			$this->telegram->text_command("uv") or
		    (
				$this->telegram->text_has("lista") &&
				$this->telegram->text_has(["usuarios", "entrenadores"]) &&
				$this->telegram->text_has(["sin", "no"], ["validar", "validados"])
			)
		){
			$this->userlist_verified();
			$this->end();
		}

		elseif(
			$this->telegram->text_has(["soy", "es", "eres"], ["admin", "administrador"], TRUE) &&
			$this->telegram->words() <= 5
		){
			$target = ($this->telegram->has_reply ? $this->telegram->reply_target('forward') : $this->user->id);
			$this->check_admin($target);
			$this->end();
		}

		elseif(
			$this->telegram->text_command("count") or
			$this->telegram->text_has("lista de", ["miembros", "usuarios", "entrenadores"])
		){
			$this->count();
			$this->end();
		}

		elseif(
			$this->telegram->text_has(["grupo offtopic", "/offtopic"])
		){
			$this->offtopic();
			$this->end();
		}

		elseif(
			(
				$this->telegram->text_has(["reglas", "normas"], "del grupo") or
		        $this->telegram->text_has(['dime', 'ver'], ["las reglas", "las normas", "reglas", "normas"], TRUE) or
		        $this->telegram->text_has(["/rules", "/normas"], TRUE)
		    ) and
	    	!$this->telegram->text_has(["poner", "actualizar", "redactar", "escribir", "cambiar"]) and
		){
			$this->rules();
			$this->end();
		}

		elseif(
			$this->telegram->words() <= 6 &&
		    (
		        ( $this->telegram->text_has("está") and $this->telegram->text_has("aquí") ) and
		        ( !$this->telegram->text_has(["alguno", "alguien", "que"], ["es", "ha", "como", "está"]) ) and // Alguien está aquí? - Alguno es....
		        ( !$this->telegram->text_contains(["desde"]) ) // , "este"
		    )
		){

		}
	}

	public function count(){

	}

	public function autokick(){

	}

	public function adminlist($chat = NULL){

	}

	public function userlist_verified($chat = NULL){

	}

	public function check_admin($user = NULL){
		if(empty($user)){ $user = $this->user->id; }
		//  or $user == $this->user->id)
	}

	public function check_user($search, $chat = NULL){

	}

	public function votekick(){

	}

	public function voteban(){

	}

	public function rules(){

	}

	public function welcome(){

	}

	public function offtopic(){

	}
}