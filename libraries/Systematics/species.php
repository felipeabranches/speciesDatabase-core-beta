<?php
class Species
{
    public $id;

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
            'sp.id' => 'ID',
            'sp.genus' => 'Genus',
            'sp.species' => 'Species',
            'tx.name' => 'Taxon',
            'sp.validate' => 'Validate',
            'sp.published' => 'Published'
        );
        return $ordering;
    }

    /*
     *  Retunrs TRUE if the Species is valid or published, or FALSE otherwise
     *
     *  $id         int     The Species' ID.
     */
    public function validateSpecies($id)
    {
        // Connect to DB
        $db = getDbInstance();
        
        // Query
        $cols = array('sp.validate', 'sp.published');
        $db->where('sp.id', $id);
        $row = $db->getOne('systematics_species sp', null, $cols);

        if (!$row['validate'] || !$row['published'])
        {
            return false;
            exit;
        }
        else
        {
            return true;
        }
    }
    
    /*
     *  Retunrs Species' Nomenclature, or NOT FOUND if no ID associated or if Species' invalid or unpublished
     *
     *  $id         int     The Species' ID.
     *  $noTags     bool    Set 0 for maintain <em> and <abbr> on $nomenclature, 1 for strip. Default 0.
     */
    public function getNomenclature($id, $noTags = 0)
    {
        // Connect to DB
        $db = getDbInstance();
        
        // Query
        $cols = array('sp.genus genus', 'sp.species species', 'sp.dubious dubious');
        $db->where('sp.id', $id);
        $row = $db->getOne('systematics_species sp', null, $cols);

        if (!$db->count)
        {
            return 'Species not found!';
            exit;
        }
        else
        {
            // Associates Dubious' integer data to its corresponding HTML
            switch ($row['dubious'])
            {
                case 0:
                    $dubious = '';
                    break;
                case 1:
                    $dubious = ' <abbr title="affinis">aff.</abbr>';
                    break;
                case 2:
                    $dubious = ' <abbr title="conferre">cf.</abbr>';
                    break;
                case 3:
                    $dubious = ' <abbr title="species">sp.</abbr>';
                    break;
                default:
                    $dubious = '';
            }

            // If Species isn't blank return a space and the value
            $species = (!$row['species']) ? '' : ' '.$row['species'];

            // Put all Nomenclature information into a variable, dealing with non-emphasis on sp.'s species field
            $nomenclature = ($row['dubious'] != 3) ? '<em>'.$row['genus'].$dubious.$species.'</em>' : '<em>'.$row['genus'].'</em>'.$dubious.$species;

            // If stripTags' on, then strip tags from Nomenclature
            if ($noTags) $nomenclature = strip_tags($nomenclature);

            return $nomenclature;
        }
    }

    /*
     *  Retunrs all Authorings associated with the Species, or FALSE otherwise
     *
     *  $id         int     The Species' ID
     *  $yearSpacer string  Wich string separates the Taxonomists from the Year, default = ' '
     */
    public function getAuthoring($id, $yearSpacer = ' ')
    {
        // Connect to DB
        $db = getDbInstance();
        
        // Query
        $cols = array('sp.revised revised', 'sp.year year', 'GROUP_CONCAT(tt.name) taxonomists');
        $db->join('systematics_species sp', 'sptt.id_species = sp.id', 'left');
        $db->join('systematics_taxonomists tt', 'sptt.id_taxonomist = tt.id', 'left');
        $db->where('sp.id', $id);
        $db->groupBy('sp.id');
        $row = $db->getOne('systematics_taxonomists_map sptt', $cols);

        if (!$row['taxonomists'])
        {
            return false;
            exit;
        }
        else
        {
            // Process how to display the Taxonomists
            $taxonomist = explode(',', $row['taxonomists']);
            if (count($taxonomist) == 1)
            {
                $taxonomist = $row['taxonomists'];
            }
            else
            {
                $last = array_pop($taxonomist);
                $firsts = implode(', ', $taxonomist);
                $taxonomist = sprintf('%s & %s', $firsts, $last);
            }

            // Put all Authoring information into a variable
            $authoring = $taxonomist.$yearSpacer.$row['year'];

            // Put Authoring inside a parentesis only if it's revised
            if ($row['revised']) $authoring = '('.$authoring.')';

            return $authoring;
        }
    }

    /*
     *  $id         int     The Species' ID
     */
    public function getDetails($id)
    {
        // Connect to DB
        $db = getDbInstance();
        
        // Query
        $cols = array('sp.etymology etymology', 'sp.common_name common_name', 'sp.distribution distribution', 'sp.habitat habitat', 'sp.description description', 'sp.note note', 'sp.image image');
        $db->where('sp.id', $id);
        $details = $db->getOne('systematics_species sp', null, $cols);

        return $details;
    }
    
    /*
     *  $id         int     The Species' ID
     */
    public function getSynonyms($id)
    {
        // Connect to DB
        $db = getDbInstance();
        
        // Query
        $cols = array('sp.id id');
        $db->where('sp.redirect', $id);
        $db->where('sp.validate', 0);
        $rows = $db->get('systematics_species sp', null, $cols);

        return $rows;
    }
}
?>
