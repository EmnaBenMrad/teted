<?php

require_once "class.writeexcel_workbook.inc.php";
require_once "class.ole_pps_root.php";
require_once "class.ole_pps_file.php";

class writeexcel_workbookbig extends writeexcel_workbook {

    function writeexcel_workbookbig($filename) {
        $this->writeexcel_workbook($filename);
    }

    function _store_OLE_file() {
        $file=new ole_pps_file(asc2ucs("Book"));
        $file->append($this->_data);

        for ($c=0;$c<sizeof($this->_worksheets);$c++) {
            $worksheet=&$this->_worksheets[$c];
            while ($data=$worksheet->get_data()) {
                $file->append($data);
            }
            $worksheet->cleanup();
        }

        $ole=new ole_pps_root(false, false, array($file));
        $ole->save($this->_filename);
    }

}

?>
