<?php
// Species class
require_once 'libraries/Systematics/Species.php';
$species = new Species();

// Connect to DB
$db = getDbInstance();

$db->join('status_distributions dist', 'sp.id = dist.id_species', 'LEFT');
$db->where('sp.published', 1);
$db->where('sp.validate', 1);
$db->where('dist.published', 1);
$db->where('dist.id_unit', 1);
$db->where('dist.id_unit', 1);
$db->groupBy('sp.id');
$spTotal = $db->get('systematics_species sp', null, 'count(*)');
?>
<!-- Total Species -->
<p>Espécies no total: <?php echo count($spTotal); ?></p>

<?php
$db->join('status_distributions dist', 'sp.id = dist.id_species', 'LEFT');
$db->where('sp.published', 1);
$db->where('sp.validate', 1);
$db->where('dist.published', 1);
$db->where('dist.status', 2);
$db->where('dist.id_unit', 1);
$db->groupBy('sp.id');
//$db->orderBy('sp.genus', 'asc');
//$db->orderBy('sp.species', 'asc');
$spEndemics = $db->get('systematics_species sp', null, 'sp.id id');
?>
<!-- Endemic Species -->
<p>Espécies endêmicas: <?php echo count($spEndemics); ?></p>
<!--ul>
<?php foreach ($spnR as $row): ?>
    <li><span class="badge badge-light"><a href="species_details.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $species->getNomenclature($row['id']); ?></a></span></li>
<?php endforeach; ?>
</ul-->

<?php
$db->join('status_distributions dist', 'sp.id = dist.id_species', 'LEFT');
$db->where('sp.published', 1);
$db->where('sp.validate', 1);
$db->where('dist.published', 1);
$db->where('dist.status', 3);
$db->where('dist.id_unit', 1);
$db->groupBy('sp.id');
$db->orderBy('sp.genus', 'asc');
$db->orderBy('sp.species', 'asc');
$spExotics = $db->get('systematics_species sp', null, 'sp.id id');
?>
<!-- Exotic Species -->
<p>Espécies exóticas: <?php echo count($spExotics); ?></p>
<ul>
<?php foreach ($spExotics as $row): ?>
    <li><span class="badge badge-light"><a href="species_details.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $species->getNomenclature($row['id']); ?></a></span></li>
<?php endforeach; ?>
</ul>

<?php
$db->join('status_distributions dist', 'sp.id = dist.id_species', 'LEFT');
$db->where('sp.published', 1);
$db->where('sp.validate', 1);
$db->where('sp.dubious', 3);
$db->where('sp.species', '%n.%', 'like');
$db->where('dist.published', 1);
$db->where('dist.id_unit', 1);
$db->groupBy('sp.id');
$db->orderBy('sp.genus', 'asc');
$db->orderBy('sp.species', 'asc');
$spNew = $db->get('systematics_species sp', null, 'sp.id id');
?>
<!-- Species without description -->
<p>Espécies sem descrição: <?php echo count($spNew); ?></p>
<ul>
<?php foreach ($spNew as $row): ?>
    <li><span class="badge badge-light"><a href="species_details.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $species->getNomenclature($row['id']); ?></a></span></li>
<?php endforeach; ?>
</ul>

<?php
$db->join('status_distributions dist', 'sp.id = dist.id_species', 'LEFT');
$db->where('sp.published', 1);
$db->where('sp.validate', 1);
$db->where('sp.year', '2010', '>=');
$db->where('dist.published', 1);
$db->where('dist.id_unit', 1);
$db->groupBy('sp.id');
$db->orderBy('sp.genus', 'asc');
$db->orderBy('sp.species', 'asc');
$spRecent = $db->get('systematics_species sp', null, 'sp.id id');
?>
<!-- Species description after 2010 -->
<p>Espécies descritas após 2010: <?php echo count($spRecent); ?></p>
<ul>
<?php foreach ($spRecent as $row): ?>
    <li><span class="badge badge-light"><a href="species_details.php?id=<?php echo $row['id']; ?>" target="_blank"><?php echo $species->getNomenclature($row['id']); ?></a></span></li>
<?php endforeach; ?>
</ul>
