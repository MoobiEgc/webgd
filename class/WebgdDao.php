<?php

require_once($CFG->dirroot . '/blocks/webgd/commons/TableResouces.php');

class WebgdDao {

    private $DB;
    private $CFG;

    function __construct() {
        global $DB, $CFG;
        $this->DB = $DB;
        $this->CFG = $CFG;
    }

    public function findPageById($id) {
        return $this->DB->get_record(TableResoucer::$TABLE_PAGINA, array('id' => $id));
    }

    public function deleteRecordByTablePageOrder() {
        $this->deleteRecordByTable(TableResoucer::$TABLE_PAGE_ORDER);
    }

    public function insertRecordInTablePageOrder($paginaOrder, $returnId = true, $bulk = false) {
        return $this->DB->insert_record(TableResoucer::$TABLE_PAGE_ORDER, $paginaOrder, $returnId, $bulk);
    }

    public function deleteRecordByTable($table) {
        return $this->DB->execute("DELETE FROM {$this->CFG->prefix}$table");
    }

    public function findChildrenByHabilityAndVisible($idParent, $visivel = 1, $habilitado = 1) {
        $sql = "SELECT p.id, p.nome, po.parent, p.link, p.link_url, p.url_icon
                  FROM {block_webgd_pagina} p
             LEFT JOIN {block_webgd_pagina_order} po ON p.id = po.page
                 WHERE po.parent = ? 
                       AND p.habilitado = ? 
                       AND p.visivel = ?
              ORDER BY po.id, po.parent";
        return $this->DB->get_records_sql($sql, array($idParent, $habilitado, $visivel));
    }

    public function findChildren($idParent) {
        $sql = "SELECT p.id, p.nome, po.parent
                  FROM {block_webgd_pagina} p
             LEFT JOIN {block_webgd_pagina_order} po ON p.id = po.page
                 WHERE po.parent = ?
              ORDER BY po.parent";
        return $this->DB->get_records_sql($sql, array($idParent));
    }

    public function getListFatherByHabilityAndVisible($visivel = 1, $habilitado = 1) {
        $sql = "SELECT p.id, p.nome, po.parent, p.link, p.link_url,  p.url_icon
                  FROM {block_webgd_pagina} p
             LEFT JOIN {block_webgd_pagina_order} po ON p.id = po.page
                 WHERE (po.parent = 0 OR po.parent IS NULL) 
                       AND p.habilitado = ? 
                       AND p.visivel = ?
              ORDER BY po.order, po.id";
        return $this->DB->get_records_sql($sql, array($habilitado, $visivel));
    }

    public function getListFather() {
        $sql = "SELECT p.id, p.nome, po.parent
                  FROM {block_webgd_pagina} p
             LEFT JOIN {block_webgd_pagina_order} po ON p.id = po.page
                 WHERE po.parent = 0 
                       OR po.parent IS NULL
              ORDER BY po.id, po.parent";
        return $this->DB->get_records_sql($sql);
    }

}
