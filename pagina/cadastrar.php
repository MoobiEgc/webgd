<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page provides the Administration -> ... -> Theme selector UI.
 *
 * @package core
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/blocks/webgd/form/CadastrarPaginaForm.php');
require_once($CFG->dirroot.'/blocks/webgd/commons/TableResouces.php');

$PAGE->set_url('/course/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');

if (!has_capability('block/webgd:cadastrarPagina', context_system::instance())) {
	redirect($CFG->wwwroot, get_string('erropermissao', 'block_webgd'), 10);
}

$codigo  = optional_param('codigo', 0, PARAM_INT);

$pagina = null;

$title = get_string('cabecalhoCadastrarPagina','block_webgd');

if($codigo > 0){
  	$pagina = $DB->get_record(TableResoucer::$TABLE_PAGINA, array('id' => $codigo));
  	$title = get_string('cabecalhoEditarPagina','block_webgd');
}

echo $OUTPUT->header('themeselector');
echo $OUTPUT->heading($title);

$mform = new CadastrarPaginaForm(null, array('pagina' => $pagina));

if ($data = $mform->get_data()) {
	if($pagina == null){
		$pagina = new stdClass();
	}
        
        //**NÃO ESTÁ SENDO ENVIADO O ARQUIVO DE ICONE - O que está sendo usado é o "nome_da_classe.png" na pasta do tema**//
//	if(!isset($data->codigo)){
//		$realfilename = $mform->get_new_filename('file'); // this gets the name of the file
//                
//		$random = rand(); // generate some random number
//		$new_file = $random.'_'.$realfilename; //add some random string to the file
//		$dst = $CFG->dataroot."/blocks/webgd/signwriting/$new_file";  // directory name+ new filename
//
//		if(!$mform->save_file('file',$dst, true)){
//			echo 'erro ao upar arquivo';
//			die;
//		}
//
//		$realfilename = $mform->get_new_filename('icone'); // this gets the name of the file
//
//		$random = rand(); // generate some random number
//		$new_file = $random.'_'.$realfilename; //add some random string to the file
//		$dst = $CFG->dataroot."/blocks/webgd/icone/$new_file";  // directory name+ new filename
//                
//		if(!$mform->save_file('icone',$dst, true)){
//			echo 'erro ao enviar arquivo de icone';
//			die;
//		}
//	}
	
	$pagina->nome = $data->nome;
	$pagina->titulo = $data->titulo; 
	$pagina->conteudo = $data->conteudo['text']; 
	$pagina->visivel = $data->visivel;
	$pagina->habilitado = $data->habilitado;
	$pagina->link = $data->yesno;
	$pagina->link_url = null;
	$pagina->url_icon = $dst;
	
	if($data->yesno == '1'){
		if(!empty($data->link_url)){
			$pagina->link_url = $data->link_url;
		}
	}
		
	$msg = get_string('msgErro', 'block_webgd');
	
	if(isset($data->codigo)){
		$pagina->id = $data->codigo;
		if($DB->update_record_raw(TableResoucer::$TABLE_PAGINA, $pagina, true)){
			$msg = get_string('msgPaginaEditadaSucesso', 'block_webgd');
		}
	}else{
		$pagina->icone = null;//"{$CFG->dataroot}/blocks/webgd/{$mform->get_new_filename('attachment')}";
		if($DB->insert_record(TableResoucer::$TABLE_PAGINA, $pagina, true)){
			$msg = get_string('msgPaginaCadastradaSucesso', 'block_webgd');
		}
	}
		redirect($CFG->wwwroot.'/blocks/webgd/index.php', $msg, 10);
} else {
	$mform->display();
}
echo $OUTPUT->footer();
