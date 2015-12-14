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
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot.'/blocks/webgd/commons/TableResouces.php');
require_once($CFG->dirroot.'/blocks/webgd/class/WebgdDao.php');

if (!has_capability('block/webgd:gerenciarOrdem', context_system::instance())) {
	redirect($CFG->wwwroot, get_string('erropermissao', 'block_webgd'), 10);
}

require_login(1);

global $USER;

$PAGE->set_url('/course/index.php');

$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');


echo $OUTPUT->header(get_string('cabecalhoGerenciamentoOrdemMenus','block_webgd'));
echo $OUTPUT->heading(get_string('cabecalhoGerenciamentoOrdemMenus','block_webgd'));

$webgdDao = new WebgdDao();

if(isset($_POST['ativo'])){
	
	$orderString = explode('&',str_replace(']','',str_replace('list[', '', $_POST['ativo'])));

	if(count($orderString) > 1){

		$webgdDao->deleteRecordByTablePageOrder();

		$pageOrder = new stdClass();

		try{
			$transaction = $DB->start_delegated_transaction();

			foreach ($orderString as $order){
				$value = explode('=', $order);

				$pageOrder->page = $value[0];
				$pageOrder->parent = 0;
					
				if($value[1] != "null"){
					$pageOrder->parent = $value[1];
				}
					
				$webgdDao->insertRecordInTablePageOrder($pageOrder);
			}
			$transaction->allow_commit();
			
			redirect($CFG->wwwroot.'/blocks/webgd/order.php',  get_string('msgOrdemSalvaSucesso','block_webgd'), 10);
		} catch(Exception $e) {
			$transaction->rollback($e);
			redirect($CFG->wwwroot.'/blocks/webgd/order.php',  get_string('msgErro','block_webgd'), 10);
		}
	}
}else{
	//TODO COMPONENTIZAR FELIPE 01/07/2014
	?>
<script>

		(function(){
			if (!/*@cc_on!@*/0) return;
			var e = ("abbr article aside audio canvas command datalist details figure figcaption footer "+
				"header hgroup mark meter nav output progress section summary time video").split(' '),
			i=e.length;
			while (i--) {
			document.createElement(e[i])
			}
		})(document.documentElement,'className');

	</script>


<script
	src="http://mjsarfatti.com/sandbox/nestedSortable/jquery-1.7.2.min.js"></script>
<script
	src="http://mjsarfatti.com/sandbox/nestedSortable/jquery-ui-1.8.16.custom.min.js"></script>
<script
	src="http://mjsarfatti.com/sandbox/nestedSortable/jquery.ui.touch-punch.js"></script>
<script
	src="<?php echo $CFG->wwwroot;?>/blocks/webgd/nestedSortable.js"></script>


<script>
	$(document).ready(function(){
		$('ol.sortable').nestedSortable({
			forcePlaceholderSize: true,
			handle: 'div',
			helper:	'clone',
			items: 'li',
			opacity: .6,
			placeholder: 'placeholder',
			revert: 250,
			tabSize: 25,
			tolerance: 'pointer',
			toleranceElement: '> div',
			maxLevels: 3,

			isTree: true,
			expandOnHover: 700,
			startCollapsed: true
		});

		$('.disclose').on('click', function() {
			$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
		})

		$('#serialize').click(function(){
			serialized = $('ol.sortable').nestedSortable('serialize');
			$('#serializeOutput').text(serialized+'\n\n');
		})

		$('#toHierarchy').click(function(e){
			hiered = $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0});
			hiered = dump(hiered);
			(typeof($('#toHierarchyOutput')[0].textContent) != 'undefined') ?
			$('#toHierarchyOutput')[0].textContent = hiered : $('#toHierarchyOutput')[0].innerText = hiered;
		})

		$('#toArray').click(function(e){
			arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
			arraied = dump(arraied);
			(typeof($('#toArrayOutput')[0].textContent) != 'undefined') ?
			$('#toArrayOutput')[0].textContent = arraied : $('#toArrayOutput')[0].innerText = arraied;
		})

		$("#form").submit(function( event ) {
			arraied = $('ol.sortable').nestedSortable('toArray', {startDepthCount: 0});
			arraied = dump(arraied);
			$("<input type='hidden' name='ativo' value='"+$('ol.sortable').nestedSortable('serialize')+"'/>").appendTo('#form'); 
		});	
	});

	function dump(arr,level) {
		var dumped_text = "";
		if(!level) level = 0;

		//The padding given at the beginning of the line.
		var level_padding = "";
		for(var j=0;j<level+1;j++) level_padding += "    ";

		if(typeof(arr) == 'object') { //Array/Hashes/Objects
			for(var item in arr) {
				var value = arr[item];

				if(typeof(value) == 'object') { //If it is an array,
					dumped_text += level_padding + "'" + item + "' ...\n";
					dumped_text += dump(value,level+1);
				} else {
					dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
				}
			}
		} else { //Strings/Chars/Numbers etc.
			dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
		}
		return dumped_text;
	}

</script>

<!-- Piwik -->
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://mjsarfatti.com/piwik/" : "http://mjsarfatti.com/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script>
<noscript>
	<p>
		<img src="http://mjsarfatti.com/piwik/piwik.php?idsite=1"
			style="border: 0" alt="" />
	</p>
</noscript>
<style type="text/css">
pre,code {
	font-size: 12px;
}

pre {
	width: 100%;
	overflow: auto;
}

small {
	font-size: 90%;
}

small code {
	font-size: 11px;
}

.placeholder {
	outline: 1px dashed #4183C4;
	/*-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			margin: -1px;*/
}

.mjs-nestedSortable-error {
	background: #fbe3e4;
	border-color: transparent;
}

ol {
	margin: 0;
	padding: 0;
	padding-left: 30px;
}

ol.sortable,ol.sortable ol {
	margin: 0 0 0 25px;
	padding: 0;
	list-style-type: none;
}

ol.sortable {
	margin: 4em 0;
}

.sortable li {
	margin: 5px 0 0 0;
	padding: 0;
}

.sortable li div {
	border: 1px solid #d4d4d4;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	border-color: #D4D4D4 #D4D4D4 #BCBCBC;
	padding: 6px;
	margin: 0;
	cursor: move;
	background: #f6f6f6;
	background: -moz-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #ededed 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff),
		color-stop(47%, #f6f6f6), color-stop(100%, #ededed) );
	background: -webkit-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #ededed 100%);
	background: -o-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #ededed 100%);
	background: -ms-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #ededed 100%);
	background: linear-gradient(to bottom, #ffffff 0%, #f6f6f6 47%, #ededed 100%);
	filter: progid : DXImageTransform.Microsoft.gradient (   startColorstr =
		'#ffffff', endColorstr = '#ededed', GradientType = 0 );
}

.sortable li.mjs-nestedSortable-branch div {
	background: -moz-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
	background: -webkit-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #f0ece9 100%);
}

.sortable li.mjs-nestedSortable-leaf div {
	background: -moz-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
	background: -webkit-linear-gradient(top, #ffffff 0%, #f6f6f6 47%, #bcccbc 100%);
}

li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
	border-color: #999;
	background: #fafafa;
}

.disclose {
	cursor: pointer;
	width: 10px;
	display: none;
}

.sortable li.mjs-nestedSortable-collapsed>ol {
	display: none;
}

.sortable li.mjs-nestedSortable-branch>div>.disclose {
	display: inline-block;
}

.sortable li.mjs-nestedSortable-collapsed>div>.disclose>span:before {
	content: '+ ';
}

.sortable li.mjs-nestedSortable-expanded>div>.disclose>span:before {
	content: '- ';
}

p,ol,ul,pre,form {
	margin-top: 0;
	margin-bottom: 1em;
}

dl {
	margin: 0;
}

dd {
	margin: 0;
	padding: 0 0 0 1.5em;
}

code {
	background: #e5e5e5;
}

input {
	vertical-align: text-bottom;
}

.notice {
	color: #c33;
}
</style>
<section id="demo">
<ol class="sortable ui-sortable">

<?php

$listaPaginas = $webgdDao->getListFather();

foreach ($listaPaginas as $pagina){
	if($listChilden = $webgdDao->findChildren($pagina->id)){
		echo '<li id="list_'.$pagina->id.'" class="mjs-nestedSortable-branch mjs-nestedSortable-collapsed"><div><span class="disclose"><span></span></span>'.$pagina->nome.'</div>';
		foreach ($listChilden as $childen){
			echo '<ol>';
			if($listChilden2 = $webgdDao->findChildren($childen->id)){
				echo '<li id="list_'.$childen->id.'" class="mjs-nestedSortable-branch mjs-nestedSortable-collapsed"><div><span class="disclose"><span></span></span>'.$childen->nome.'</div>';
				foreach ($listChilden2 as $childen2){
					echo '<ol>';
					echo '<li id="list_'.$childen2->id.'" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>'.$childen2->nome.'</div></li>';
					echo '</ol>';
				}
			}else{
				echo '<li id="list_'.$childen->id.'" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>'.$childen->nome.'</div></li>';
			}
			echo '</ol>';
		}
	}else{
		echo '<li id="list_'.$pagina->id.'" class="mjs-nestedSortable-leaf"><div><span class="disclose"><span></span></span>'.$pagina->nome.'</div></li>';
	}
}
?>
</ol>
<form action=" " id="form" method="post">
	<input type="submit" value="salvar">
</form>
</section>
<?php
	echo $OUTPUT->single_button($CFG->wwwroot.'/blocks/webgd/index.php', get_string('cacelar','block_webgd'));   
}
echo $OUTPUT->footer();
?>