<?php
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot.'/blocks/webgd/commons/TableResouces.php');

if (!has_capability('block/webgd:deletarPagina', context_system::instance())) {
	redirect($CFG->wwwroot, get_string('erropermissao', 'block_webgd'), 10);
}

$PAGE->set_url('/course/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');

$codigo  = optional_param('codigo', 0, PARAM_INT);

$pagina = $DB->get_record(TableResoucer::$TABLE_PAGINA, array('id' => $codigo));

$msg = 'Erro registro não encontrado';

if(is_object($pagina)){
	$msg = 'Erro ao excluir';
	if($DB->delete_records(TableResoucer::$TABLE_PAGINA, array('id' => $pagina->id))){
		$msg =  'Registro excluido com sucesso';
	} 
}

echo $OUTPUT->header('themeselector');
echo $OUTPUT->heading('Gerenciamento de pagina');

redirect($CFG->wwwroot.'/blocks/webgd/index.php', $msg, 10);
	
echo $OUTPUT->footer();
	
