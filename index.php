<?php 
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/blocks/webgd/commons/TableResouces.php');
require_login(1);
if (!has_capability('block/webgd:administracao', context_system::instance())) {
	redirect($CFG->wwwroot, get_string('erropermissao', 'block_webgd'), 10);
}
$PAGE->set_url('/course/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');

echo $OUTPUT->header('');
echo $OUTPUT->heading('Gerenciamento de pagina');


$listaPaginas = $DB->get_records(TableResoucer::$TABLE_PAGINA);

?>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script>
function editar(id) {
	$('<form action="pagina/cadastrar.php" method="GET"/>').append($('<input type="hidden" name="codigo" value="' + id + '">')).appendTo($(document.body)).submit();
}

function excluir(id) {
	if (confirm('Deseja realmente excluir este registro?')){ 
		$('<form action="pagina/excluir.php" method="GET"/>').append($('<input type="hidden" name="codigo" value="' + id + '">')).appendTo($(document.body)).submit();
	}
}

</script>

<style>
.CSSTableGenerator {
	margin:0px;padding:0px;
	width:100%;
	border:1px solid #000000;
	
	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;
	
	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;
	
	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;
	
	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}.CSSTableGenerator table{
    border-collapse: collapse;
        border-spacing: 0;
	width:100%;
	height:100%;
	margin:0px;padding:0px;
}.CSSTableGenerator tr:last-child td:last-child {
	-moz-border-radius-bottomright:0px;
	-webkit-border-bottom-right-radius:0px;
	border-bottom-right-radius:0px;
}
.CSSTableGenerator table tr:first-child td:first-child {
	-moz-border-radius-topleft:0px;
	-webkit-border-top-left-radius:0px;
	border-top-left-radius:0px;
}
.CSSTableGenerator table tr:first-child td:last-child {
	-moz-border-radius-topright:0px;
	-webkit-border-top-right-radius:0px;
	border-top-right-radius:0px;
}.CSSTableGenerator tr:last-child td:first-child{
	-moz-border-radius-bottomleft:0px;
	-webkit-border-bottom-left-radius:0px;
	border-bottom-left-radius:0px;
}.CSSTableGenerator tr:hover td{
	
}
.CSSTableGenerator tr:nth-child(odd){ background-color:#e2e0e0; }
.CSSTableGenerator tr:nth-child(even)    { background-color:#ffffff; }.CSSTableGenerator td{
	vertical-align:middle;
	
	
	border:1px solid #000000;
	border-width:0px 1px 1px 0px;
	text-align:left;
	padding:7px;
	font-size:10px;
	font-family:Arial;
	font-weight:normal;
	color:#000000;
}.CSSTableGenerator tr:last-child td{
	border-width:0px 1px 0px 0px;
}.CSSTableGenerator tr td:last-child{
	border-width:0px 0px 1px 0px;
}.CSSTableGenerator tr:last-child td:last-child{
	border-width:0px 0px 0px 0px;
}
.CSSTableGenerator tr:first-child td{
		background:-o-linear-gradient(bottom, #416280 5%, #416280 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #416280), color-stop(1, #416280) );
	background:-moz-linear-gradient( center top, #416280 5%, #416280 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#416280", endColorstr="#416280");	background: -o-linear-gradient(top,#416280,416280);

	background-color:#416280;
	border:0px solid #000000;
	text-align:center;
	border-width:0px 0px 1px 1px;
	font-size:14px;
	font-family:Arial;
	font-weight:bold;
	color:#ffffff;
}
.CSSTableGenerator tr:first-child:hover td{
	background:-o-linear-gradient(bottom, #416280 5%, #416280 100%);	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #416280), color-stop(1, #416280) );
	background:-moz-linear-gradient( center top, #416280 5%, #416280 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr="#416280", endColorstr="#416280");	background: -o-linear-gradient(top,#416280,416280);

	background-color:#416280;
}
.CSSTableGenerator tr:first-child td:first-child{
	border-width:0px 0px 1px 0px;
}
.CSSTableGenerator tr:first-child td:last-child{
	border-width:0px 0px 1px 1px;
}
</style>

<div class="CSSTableGenerator" >
                <table >
                    <tr>
                        <td>
                            Nome
                        </td>
                        <td>
                            Titulo
                        </td>
                        <td>
                            Habilitado
                        </td>
                        <td>
                            Visivel
                        </td>
                         <td>
                            Editar
                        </td>
                         <td>
                            Excluir
                        </td>
                    </tr>
                    <?php 
	                     foreach ($listaPaginas as $pagina){
		                   
							if (has_capability('block/webgd:editarPagina', context_system::instance())) {
		                    	$editar =  "<img src='http://findicons.com/files/icons/1688/web_blog/48/pencil_small.png' id='".$pagina->id."' height='24px' width='24px' onclick=\"editar(this.id)\">";
		                    }
		                    
		                    if (has_capability('block/webgd:deletarPagina', context_system::instance())) {
		                    	$excluir =  "<img src='http://findicons.com/files/icons/573/must_have/48/delete.png' id='".$pagina->id."' height='24px' width='24px' onclick=\"excluir(this.id)\">";
		                    }
							
						
							if($pagina->visivel == '1'){
								$visivel = "Sim";
							}else{
								$visivel = "Não";
							}
							
							if($pagina->habilitado == '1'){
								$habilitado = "Sim";
							}else{
								$habilitado = "Não";
							}
							
	                    	echo "<tr>
		                        <td >
	                    		   {$pagina->nome}
		                        </td>
		                        <td>
		                           {$pagina->titulo}
		                        </td>
		                        <td>
		                           {$habilitado}
		                        </td>
		                        <td>
		                           {$visivel}
		                        </td>
		                           <td>
		                          	$editar
		                        </td>
		                           <td>
		                            $excluir
		                        </td>
		                    </tr>";
	                    }
                    ?>
                </table>
            </div>
<?php 

if (has_capability('block/webgd:cadastrarPagina', context_system::instance())) {
	echo $OUTPUT->single_button($CFG->wwwroot.'/blocks/webgd/pagina/cadastrar.php', get_string('cadastrar','block_webgd'));                    	
}

if (has_capability('block/webgd:gerenciarOrdem', context_system::instance())) {
	echo $OUTPUT->single_button($CFG->wwwroot.'/blocks/webgd/order.php', get_string('gerenciarOrdem','block_webgd'));	                    	
}

echo $OUTPUT->footer();
