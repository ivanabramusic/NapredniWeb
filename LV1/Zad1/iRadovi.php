<?php
interface iRadovi {
    public function create($naziv_rada, $tekst_rada, $link_rada, $oib_tvrtke);
    public function save($radovi);
    public function read();
}
?>
