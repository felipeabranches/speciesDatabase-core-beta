<?php
class Taxa
{
    public function __construct()
    {
        //echo __CLASS__.' class instanciated!<br />';
    }

    public function __destruct()
    {
        //echo __CLASS__.' class destructed!<br />';
    }

    /*
     *
     */
    public function getOrderValues()
    {
        $ordering = array(
            'tx.id' => 'ID',
            'tx.name' => 'Name',
            'txtp.name' => 'Type',
            'prnt.name' => 'Parent',
            'tx.published' => 'Published'
        );
        return $ordering;
    }
}
?>
