<?php
require_once("$CFG->libdir/formslib.php");
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir. '/coursecatlib.php');

class CadastrarPaginaForm extends moodleform {
    
    //TODO: verificar a necessidade dessa página existir, pois existe outra igual (ou quase) dentro de /blocks/webgd/pagina/cadastrar
	//Add elements to form
	
	public function scriptJs(){
		?>
		<script>
			
			function change(){
				check();
			}
			
			function check(){
				if (document.getElementById('id_yesno_0').checked) {
					document.getElementById("id_link_url").disabled = true; 
				}

				if (document.getElementById('id_yesno_1').checked) {
					document.getElementById("id_link_url").disabled = false; 
				}
			}
		</script>
		
		<?php
	}
	

	public function definition() {
		global $CFG;

		$this->scriptJs();
		
		$pagina = $this->_customdata['pagina'];

		$mform = $this->_form; // Don't forget the underscore!

		$mform->addElement('text', 'nome', get_string('labelNome','block_webgd'));
		$mform->addRule('nome', get_string('labelValidacaoNome','block_webgd'), 'required', null, 'client');
                $mform->setType('nome', PARAM_TEXT);

		$mform->addElement('text', 'titulo', get_string('labelTitulo', 'block_webgd'));
		$mform->addRule('titulo', get_string('labelValidacaoTitulo','block_webgd'), 'required', null, 'client');
                $mform->setType('titulo', PARAM_TEXT);
		 
		//echo $CFG->wwwroot.'/file.php/1/blocks/webgd/icone/1716516153_logo-shop-red.png';

//		if(!$pagina){
//			$mform->addElement('filepicker', 'icone', "icone", null, array('maxbytes' => 1024*1024, 'accepted_types' =>array('*')));
		 
//			$mform->addElement('filepicker', 'file', "Enviar imagem em Signwriting", null, array('maxbytes' => 1024*1024, 'accepted_types' =>array('*')));
//		} 
		
		/* 
		if(!$pagina){
			$mform->addElement('file', 'attachment', 'Icone Lateral');
			$mform->addRule('attachment', get_string('labelValidacaoArquivo', 'block_webgd'), 'required', null, 'client');
		}*/
		 
		$options = array(
   		 	'1' => 'Sim',
		    '0' => 'Não',
		);

		$select = $mform->addElement('select', 'visivel', get_string('labelPaginaVisivelLogado', 'block_webgd'), $options);

		$select = $mform->addElement('select', 'habilitado', get_string('labelPaginaHabilitada', 'block_webgd'), $options);

		$radioarray = array();
		
		$attributes = array('onchange'=> 'change()');
		$radioarray[] =& $mform->createElement('radio', 'yesno', '', 'Sim', 1, $attributes);
		$attributes = array('onchange'=> 'change()');
		$radioarray[] =& $mform->createElement('radio', 'yesno', '', 'Não', 0, $attributes);
		$mform->addGroup($radioarray, 'radioar', 'Link Externo', array(' '), false);
		
		$mform->setDefault('yesno', 1);

		$atributes = array('maxlenght' => '300');
		$mform->addElement('text', 'link_url', 'URL', $atributes);
                $mform->setType('link_url', PARAM_TEXT);
				
		$editoroptions = array('maxlenght' => '300');

		$mform->addElement('editor','conteudo', 'Conteudo Pagina', null, $editoroptions);
		$mform->addRule('conteudo', get_string('labelValidacaoConteudoPagina', 'block_webgd'), 'required', null, 'client');
		$mform->setType('conteudo', PARAM_RAW);

		$buttonarray=array();
		$buttonarray[] = &$mform->createElement('submit', 'submitbutton', get_string('savechanges'));
		$buttonarray[] = &$mform->createElement('reset', 'resetbutton', get_string('revert'));
		$buttonarray[] = &$mform->createElement('button', 'cancelar', 'Cancelar', 'onclick=location.href="'.$CFG->wwwroot.'/blocks/webgd/index.php"');
		$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
		$mform->closeHeaderBefore('buttonar');
		
		$this->fillIn($mform, $pagina);
		
	}

	private function fillIn($mform, $pagina) {
		if($pagina){
			$mform->addElement('hidden', 'codigo');
			$mform->setDefault('codigo', $pagina->id);
			$mform->setDefault('nome', $pagina->nome);
			$mform->setDefault('titulo', $pagina->titulo);
			$mform->setDefault('visivel', $pagina->visivel);
			$mform->setDefault('habilitado', $pagina->habilitado);
			$mform->setDefault('conteudo',  array('text'=>$pagina->conteudo));
			$mform->setDefault('link_url', $pagina->link_url);
			$mform->setDefault('yesno', $pagina->link);
			$mform->disabledIf('link_url', 'yesno', 'eq', 0);
		}
	}
	//Custom validation should be added here
	function validation($data, $files) {
		$errors = array();
		if($data['yesno'] == '1'){
			if(empty($data['link_url'])){
				$errors['link_url']= 'Link Url Vazia';
			}
		}
		 
		
		return $errors;
	}
}