<?php
class Taxonomists
{
    public function __construct()
    {
        //echo __CLASS__.' class instanciated!<br />';
    }

    public function __destruct()
    {
        //echo __CLASS__.' class destructed!<br />';
    }

    public function getTaxonomists($id, $order_by, $filter_by)
    {
/*
        $id = (!$_GET['id'] || $id == 0 || $id == NULL) ? '' : ' AND tt.id IN ('.$_GET['id'].')';
        $order_by = (!$_GET['order_by'] || $order_by == NULL) ? 'id' : $_GET['order_by'];
        $filter_by = ($filter_by =='published') ? '' : ' OR tt.published = 0';

        $sql = 'SELECT
                WHERE (tt.published = 1'.$filter_by.')'.$id.'
                ORDER BY tt.'.$order_by.'
                ;';
*/
        // Connect to DB
        $db = getDbInstance();

        // Query
        $cols = array ('tt.id id', 'tt.name name', 'tt.description description', 'tt.note note', 'tt.image image', 'tt.published published');
        $taxonomists = $db->get('systematics_taxonomists tt', null, $cols);

        if (!$taxonomists)
        {
            return false;
        }
        else
        {
            return $taxonomists;
        }
    }

    public function getTaxonomist($id)
    {
        // Connect to DB
        $db = getDbInstance();

        // Query
        $cols = array ('tt.id id', 'tt.name name', 'tt.description description', 'tt.note note', 'tt.image image', 'tt.published published');
        $db->where('tt.published', 1);
        $db->where('tt.id', $id);
        $taxonomist = $db->getOne('systematics_taxonomists tt', $cols);

        if (!$taxonomist)
        {
            return false;
        }
        else
        {
            return $taxonomist;
        }
    }

    public function getSpeciesCount($id)
    {
    }
    
    public function getSpecies($id)
    {
        // Connect to DB
        $db = getDbInstance();

        // Query
        $cols = array ('sp.id spID', 'sp.year year');
        $db->join('systematics_taxonomists tt', 'tt.id = sptt.id_taxonomist', 'left');
        $db->join('systematics_species sp', 'sp.id = sptt.id_species', 'left');
        $db->where('tt.published', 1);
        $db->where('tt.id', $id);
        $db->where('sp.published', 1);
        $db->where('sp.validate', 1);
        $species = $db->get('systematics_taxonomists_map sptt', null, $cols);

        if (!$species)
        {
            return false;
        }
        else
        {
            return $species;
        }
    }
}
?>
