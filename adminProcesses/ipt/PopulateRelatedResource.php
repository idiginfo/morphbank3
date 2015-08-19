<?php
echo "truncate " . $iptRRTable . ";

insert into " . $iptRRTable . "
(occurrenceId, relationshipOfResource, relatedResourceId)
select occurrenceId, 'representedIn',concat ('http://www.morphbank.net/',id)
from " . $iptOccTable . ";

";

?>
