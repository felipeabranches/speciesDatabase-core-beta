<?php
class TaxaTypes
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
            'txtp.id' => 'ID',
            'txtp.name' => 'Name',
            'txtp.published' => 'Published'
        );
        return $ordering;
    }
}
?>
