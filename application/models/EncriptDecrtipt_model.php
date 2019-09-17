<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	
class EncriptDecrtipt_model extends CI_Model {

	
	private $key;
	function __construct(){
		parent::__construct();		
		$this->key = EncriptDecriptKey;
    } 
	
	
	public function str_encode($data) {
		 $EncodedData = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->key), $data, MCRYPT_MODE_CBC, md5(md5($this->key))));
		$str=strtr(base64_encode($EncodedData), '+/=', '-_-');
		return $str; 
		
		
	}
	
	public function str_decode($data) {
		$data=base64_decode(strtr($data, '-_-', '+/='));
		$DecodedData = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->key), base64_decode($data), MCRYPT_MODE_CBC, md5(md5($this->key))), "\0");
		return $DecodedData; 
		
	}
	
	
/* public function encrypt($string) {
  $result = '';
  for($i=0; $i<10; $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }
	
  return base64_encode($result);
}

public function decrypt($string) {
  $result = '';
  $string = base64_decode($string);

  for($i=0; $i<10; $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($this->key, ($i % strlen($this->key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }

  return $result;
}
 */	
	
}


