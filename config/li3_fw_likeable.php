<?php

/* Config example */

use app\models\Activities; // This is the `likeable` model

Activities::likeableConfig([
	'anonymous_likes' => false
]);

?>
