<?php
require_once ($CFG->dirroot . '/blocks/webgd/class/WebgdDao.php');

class block_webgd extends block_list {

    function init() {
        $this->title = get_string('pluginname', 'block_webgd');
    }

    public function get_content() {
        global $CFG, $USER;
        ?>

        <style>
            .titulo_menu_webgd a{
                color: #40617F !important;
                font-size: 16px !important;
                margin-left: 10px !important;
                font-weight: bold;
            }

            .block_webgd li{
                list-style-type: none !important;
            }
            .block_webgd ul{
                list-style-type: none !important;
            }

            .block_webgd li{
                padding-top: 25px !important;
                padding-bottom: 25px !important;
            }

            .block_webgd li{
                border-bottom: 2px solid #CCCBCB !important;
            }
            
            #videodiv {
                z-index:1;
            }

        </style>



        <?php
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass ();
        $this->content->items = array();
        $this->content->icons = array();

        $aumentar = $CFG->wwwroot . '/theme/clean/pix/icons/aumentar.png';

        if (has_capability('block/webgd:administracao', context_system::instance())) {
            $this->content->items[] = "<div class='linha_webgd' style='width:330px;'>
                                            <div style='float:left; width:280px;'>
						<img style='width:auto;height:auto;vertical-align: middle;' src='" . $aumentar . "'>
						<span class='titulo_menu_webgd'>
                                                    <a  title='" . get_string('administracao', 'block_webgd') . "' href='" . $CFG->wwwroot . "/blocks/webgd/index.php'>" . get_string('administracao', 'block_webgd') . "  </a>
						</span>
                                            </div>
					</div>";
        }


        $visivel = 0;

        if ($USER->id) {
            $visivel = 1;
        }

        $webgdDao = new WebgdDao ();
        $listaPaginas = $webgdDao->getListFatherByHabilityAndVisible($visivel);

        $item = "";
        foreach ($listaPaginas as $page) {
            //setando valor estatico para os icones
            $page->url_icon = $CFG->wwwroot . "/theme/clean/pix/icons/" . $page->nome . ".png";

            if ($listChilden = $webgdDao->findChildrenByHabilityAndVisible($page->id, $visivel)) {
                $this->content->items[] = $this->gerateLink($page->id, $page->nome, $page->link, $page->link_url, $page->url_icon);
                foreach ($listChilden as $childen) {
                    if ($listChilden2 = $webgdDao->findChildrenByHabilityAndVisible($childen->id, $visivel)) {
                        $this->content->items[] = $this->gerateLink($childen->id, $childen->nome, $childen->link, $childen->link_url, $page->url_icon);
                        foreach ($listChilden2 as $childen2) {
                            $this->content->items[] = $this->gerateLink($childen2->id, $childen2->nome, $childen2->link, $childen2->link_url, $page->url_icon);
                        }
                    } else {
                        $this->content->items[] = $this->gerateLink($childen->id, $childen->nome, $childen->link, $childen->link_url, $page->url_icon);
                    }
                }
            } else {
                $this->content->items[] = $this->gerateLink($page->id, $page->nome, $page->link, $page->link_url, $page->url_icon);
            }
        }


        $this->content->footer = '<!--VIDEO-->
        <div id="videodiv" class="videodiv dissmissable mobile">

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

        return $this->content;
    }

    public function get_aria_role() {
        return 'navigation';
    }

    function applicable_formats() {
        return array(
            'all' => true,
            'mod' => false,
            'my' => false,
            'admin' => false,
            'tag' => false
        );
    }

    private function gerateLink($id, $nome, $link, $urlLink, $urlIcon) {
        global $CFG;
        $target = '_self';
        $imgLibras = $CFG->wwwroot . '/theme/clean/pix/icons/mao-libras.png';
        $imgSignwrigth = $CFG->wwwroot . '/theme/clean/pix/icons/mao-signwrigth.png';

        $redhand = $CFG->wwwroot . "/blocks/webgd/redhand/" . $nome . ".png";

        $videoLibras = '#';

        if ((int) $link) {
            $url = $urlLink;
        } else {
            $url = $CFG->wwwroot . "/blocks/webgd/view.php?page=" . $id;
        }

        if (empty($urlIcon)) {
            $urlIcon = $CFG->wwwroot . '/theme/clean/pix/icons/aumentar.png';
        }
        if ($nome == 'Inicio') {
            $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/boasvindas.mp4';
        }
        if ($nome == 'Equipe') {
            $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/equipe.mp4';
        }
        if ($nome == 'Projetos') {
            $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/projetos.mp4';
        }
        if ($nome == 'Publicações') {
            $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/publicações.mp4';
        }
        if ($nome == 'Cursos') {
            $videoLibras = $CFG->wwwroot . '/blocks/webgd/videos/projecao.mp4';
            $target = "_blank";
        }
        return "<div class='linha_webgd'>

                    <div style='float:left; position:relative;'>
                      <img style='width:auto;height:auto;vertical-align: middle;' src='" . $urlIcon . "' height='26' width='26'>
                      <span class='titulo_menu_webgd'>
                          <a title='" . $nome . "' href='" . $url . "' target='" . $target . "'>$nome</a>
                      </span>
                    </div>

                    <div class='row' style='float:right; position: relative; margin-left:0;'>
                        <div class='col-lg-12'>
                            <a class='hand' href='" . $videoLibras . "'><img src='" . $imgLibras . "'></img></a>
                        </div>

                        <div class='col-lg-12'>
                            <a href='#' class='tooltip_redhand' rel='" . $redhand . "'><img src='" . $imgSignwrigth . "'></img></a>
                        </div>

                    </div>

                </div>";
    }

}
