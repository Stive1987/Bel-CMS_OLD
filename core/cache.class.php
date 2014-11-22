<?php
/**
 * Bel-CMS [Content management system]
 * @version 0.0.1
 * @link http://www.bel-cms.be
 * @link http://www.stive.eu
 * @license http://opensource.org/licenses/GPL-3.0 copyleft
 * @copyright 2014 Bel-CMS
 * @author Stive - mail@stive.eu
 */
class Cache
{
	public $dirname;    // Dossier de cache
	public $duration;   // Durée de vie du cache EN MINUTES
	public $buffer;     // Buffer (utilisé pour les méthodes start/end)

	/**
	* Initialise le cache
	* @param string $dirname Dossier contenant le cache
	* @param int $duration Durée de vie du cache
	**/
	public function __construct($dirname, $duration){
		$this->dirname = $dirname;
		$this->duration = $duration;
	}

	/**
	* Ecrit une chaine de caractère dans le cache
	* @param string $cachename Nom du fichier de cache
	* @param string $content Chaine de caractère à stocker
	**/
	public function write($cachename, $content){
		return file_put_contents($this->dirname.'/'.$cachename, $content);
	}

	/**
	* Permet de lire une variable dans le cache
	* @param string $cachename Nom du fichier de cache
	**/
	public function read($cachename){
		$file = $this->dirname.'/'.$cachename;
		if(!file_exists($file)){
			return false;
		}
		$lifetime = (time() - filemtime($file)) / 60;
		if($lifetime > $this->duration){
			return false;
		}
		return file_get_contents($file);
	}

	/**
	* Permet de supprimer un fichier de cache
	* @param string $cachename Nom du fichier de cache
	**/
	public function delete($cachename){
		$file = $this->dirname.'/'.$cachename;
		if(file_exists($file)){
			unlink($file);
		}
	}

	/**
	* Permet de nettoyer le cache, Vider tous les fichiers en cache
	**/
	public function clear(){
		$files = glob($this->dirname.'/*');
		foreach( $files as $file ) {
			unlink($file);
		}
	}

	/**
	* Inclue un fichier en utilisant le cache
	* @param string $file Fichier à inclure (chemin absolu)
	* @param string $cachename Nom du fichier de cache
	**/
	public function inc($file, $cachename = null){
		if(!$cachename){
			$cachename = basename($file);
		}
		if($content = $this->read($cachename)){
			echo $content;
			return true;
		}
		require $file;
		$content = ob_get_clean();
		$this->write($cachename, $content);
		echo $content;
		return true;
	}

	/**
	* Démarre un buffer qui permettra de mettre en cache tout le contenu jusqu'au prochain Cache::end()
	* @param string $cachename Nom du fichier de cache
	**/
	public function start($cachename){
		if($content = $this->read($cachename)){
			echo $content;
			$this->buffer = false;
			return true;
		}
		ob_start();
		$this->buffer = $cachename;
	}


	public function end(){
		if(!$this->buffer){
			return false;
		}
		$content = ob_get_clean();
		echo $content;
		$this->write($this->buffer, $content);
	}

}
