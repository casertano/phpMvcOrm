<?php 
class TesteController {
	
	public function index() {
		return new View();
	}
	
	public function testando() {
		return new View();
	}
	
	public function maisTeste() {
		
		$contact = array(
					"nome" => 'José Pereira',
					"idade" => 33,
					"web" => array(
								array(
									"email" => "teste@teste.com.br",
									"site" => "www.teste.com.br"			
								),
								array(
									"email" => "teste2@teste.com.br",
									"site" => "www.teste2.com.br"
								)
							),
					"phone" => array(
								array(
									"DDI" => "+55",
									"DDD" => "011",
									"number" => "99999999"	
								),
								array(
									"DDI" => "+55",
									"DDD" => "011",
									"number" => "66666666"
								),
								array(
									"DDI" => "+55",
									"DDD" => "011",
									"number" => "22222222"
								),
							),
					"car" => array("fusca", "brasilia")
				);
		
		$templateList = new ViewReplaceListTemplate();
		$templateList->phone = "phoneList";
		$templateList->web = "webList";
		$templateList->car = "carList";
		
		
		return new View($contact, $templateList);
	}
}
?>