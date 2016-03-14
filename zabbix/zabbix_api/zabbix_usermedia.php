<?php

define("AUTH","");	//AUTH为token

class CURL {
	public function Curl($Json) {
		$str = json_encode($Json);
		$ch = curl_init('http://zabbix.hichao.com/api_jsonrpc.php');	//需替换zabbix的uri
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $str);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen($str))
		);

		$api_output=json_decode(curl_exec($ch),'array');
		return $api_output['result'];
	}
}

class ARGV extends CURL {

	public function __construct(){
	}

	public function Guide($script_name) {
		print_r(" run : `php ".$script_name." 选项 [参数]`\n\n 选项:\n");
		echo " userlist\n smslist\n smsadd\n smsdel\n\n";
	}

	public function User_get() {
		$Json=array(
			"jsonrpc" => "2.0",
			"method" => "user.get",
			"params"=> array(
				"output" => "extend"
			),
			"auth" => constant("AUTH"),
			"id" => 1
		);
		$User_infos=$this->Curl($Json);
		echo "userid -- username\n";
		foreach($User_infos as $k => $v){
			print_r($v['userid']);
			echo " -- ";
			print_r($v['alias']);
			echo "\n";

		}
	}

	public function Usermedia_get($userid) {
		$Json=array(
			"jsonrpc" => "2.0",
			"method" => "usermedia.get",
			"params" => array(
				"output" => "extend",
				"userids" => $userid
			),
			"auth" => constant("AUTH"),
			"id" => 1
		);
		$Usermedia_infos=parent::Curl($Json);
		echo "userid -- medisid -- sms\n";
		foreach($Usermedia_infos as $k => $v){
			if(preg_match("/^1\d{10}$/",$v['sendto'])) {
				print_r($v['userid']);
				echo " -- ";
				print_r($v['mediaid']);
				echo " -- ";
				print_r($v['sendto']);
				echo "\n";
			}
		}
	}

	public function Usermedia_add($userid,$usersms) {
		$Json=array(
			"jsonrpc" => "2.0",
			"method" => "user.addmedia",
			"params" => array(
				"users" => array(
					"userid" => $userid
				),
				"medias" => array(
					"mediatypeid" => "5",
					"sendto" => $usersms,
					"active" => 0,
					"severity" =>  63,
					"period" => "1-7,00:00-24:00"
				),
			),
			"auth" => constant("AUTH"),
			"id" => 1
		);
		if(parent::Curl($Json)){
			echo "添加完成\n";
		}
	}

	public function Usermedia_del($medisid) {
		$Json=array(
			"jsonrpc" => "2.0",
			"method" => "user.deletemedia",
			"params" => array(
				 $medisid
			),
			"auth" => constant("AUTH"),
			"id" => 1
		);

		if(parent::Curl($Json)){
			echo "删除完成\n";
		}
	}

}

class MENU extends ARGV{
	public $a=null;

	public function  __construct($argv) {
		$this->a=$argv;
		if(count($this->a) < 2){
			$this->Guide($this->a[0]);
			exit;
		}
	}

	public function Menu($argv){
		switch($this->a[1]){
			case 'userlist':
				$this->User_get();
			break;
			case 'smslist':
				if(empty($this->a[2])) {
					echo " 请输入userid \n";
				}else{
					$this->Usermedia_get($this->a[2]);
				}
			break;
			case 'smsadd':
				if(preg_match("/^1\d{10}$/",$this->a[3])) {
					$this->Usermedia_add($this->a[2],$this->a[3]);
					$this->Usermedia_get($this->a[2]);
				}else{
					echo " 请按照格式执行 : `php ".$this->a[0]." ".$this->a[0]." 用户id 添加的手机号码`\h";
				}
			break;
			case 'smsdel':
				if(empty($this->a[2])) {
					echo " 请输入mediaid \n";
				}else{
					$this->Usermedia_del($this->a[2]);
					$this->Usermedia_get($this->a[2]);
				}
			break;

			default:
				$this->Guide($this->a[0]);
		}
	}
}

$menu=new MENU($argv);
$menu->Menu($argv);

?>
