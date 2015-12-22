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
require_once($CFG->dirroot . '/blocks/webgd/class/WebgdDao.php');
global $CFG;
require_login(1);
$idPage = optional_param('page', '', PARAM_INTEGER);

$PAGE->set_url('/course/index.php');
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');

echo $OUTPUT->header('');
?>

<link href="./blocks/webgd/css/font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet" />

<style>
    .titulo_pagina{
        font-weight: bold;
        font-size: 24;
        color: #41627F;
    }

    .descricao p {
        text-align: justify;
        text-indent: 2.5em;
        margin-right: 20px;
        font-size: 14px;
        margin-top: 30px;

    }

    /*VIDEOCSS*/


    .move {
        cursor:move;
    }

    div#videodiv{
        position: absolute;
        top: 200px;
        left: 300px;
        width:512px;
        height:288px;
    }

    .link{
        cursor: pointer;
    }

    .linkdisabled{
        color: #AAA;
    }

    #mainHand img {
        margin-top: -40px;
        margin-left: 400px;
        display: block;
    }


</style>


<?php
$webgdDao = new WebgdDao();

$page = $webgdDao->findPageById($idPage);


if($page && ($page->habilitado != "false")){
	$PAGE->set_cacheable(false);

	if($idPage == 13) {

	echo $OUTPUT->heading('Seja bem-vindo ao WebGD!', 2 ,'titulo_pagina');

	$videoLibras = '#';
	$videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/retas.mp4';
	$imgLibras = $CFG->wwwroot . '/theme/moobi/pix/icons/mao-libras.png';

	echo "<div><a class='hand' id='mainHand' href='" . $videoLibras . "'><img src='" . $imgLibras . "'></img></a></div>";

    if ($idPage == 13) {

        echo $OUTPUT->heading('Seja bem-vindo ao WebGD!', 2, 'titulo_pagina');

        $videoLibras = '#';
        $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/retas.mp4';
        $imgLibras = $CFG->wwwroot . '/theme/clean/pix/icons/mao-libras.png';

        echo "<div><a class='hand' id='mainHand' href='" . $videoLibras . "'><img src='" . $imgLibras . "'></img></a></div>";



        echo ' <div class="descricao">
    <p>
    O WebGD é um ambiente de aprendizagem que visa a acessibilidade a surdos e ouvintes.
    Aqui, você encontrará cursos na área de Geometria, além da possibilidade de criar comunidades de prática.
    O ambiente oferece um conjunto de ferramentas para interação, colaboração e comunicação para auxiliar atividades de discussão, criação de sinais, votação, construção de ícones e gravação de vídeos.
    Clique em Entrar na área superior direita do site.
    Para utilizar o WebGD é necessário criar uma conta e ter de preferência um e-mail do Gmail.
    </p>
    </div>

    <!--VIDEO-->
    <div id="videodiv" class="dissmissable mobile" onload="myFunction()">

            <video id="videotag" style="display:none" autoplay>
                    <source src="./blocks/webgd/videos/equipe.mp4" type=\'video/mp4; codecs="avc1.42E01E"\' />
            </video>
            <canvas width="512" height="576" id="buffer"></canvas>
            <canvas width="512" height="288" id="output" class="move"></canvas>


            <div class="controls">
                    <div class="myRow">
                            <div class="col-xs-12">
                                    <input id="playBackSlider" min="0.25" max="1.75" value="1" step="0.25" type="range">
                            </div>
                    </div>
                    <div class="playBar">

                                            <div class="controlBtn link" id="replay">
                                                    <span class="fa fa-fast-backward"></span>
                                            </div>

                                            <div class="controlBtn link" id="playPause">
                                                    <span class="fa fa-pause"></span>
                                            </div>

                                            <div class="controlBtn link" id="faster">
                                                    <span class="fa fa-forward"></span>
                                            </div>


                    </div>

                    <div class="link dismiss">&times;</div>
            </div>

    </div>
    <div id="imagediv" class="mobile dissmissable">
            <div class="move">
                <img src="#" />
                <div class="link dismiss">&times;</div>
            </div>
    </div>

    <script type="text/javascript" src="' . $CFG->wwwroot . '/blocks/webgd/js/videolibras.js"></script>
    <!--FIM DO VIDEO e imagem LIBRAS-->';

        echo "
    <script>
    $(document).ready(function(){
    $('#mainHand').click();
    });
    </script>
    ";
    } else {

        echo $OUTPUT->heading($page->titulo, 2, 'titulo_pagina');
        echo $page->conteudo;
    }
} else {
    print_error('unspecifycourseid', 'error');
}


echo $OUTPUT->footer();
?>
