The tag <?= $page ?> is used in the following tag sets:<br>
<?=

$page->tagsets();

?>

<?php

use Kirby\Uuid\Uuids;

dump(Uuids::cache());
dump(Uuids::populate());

?>
