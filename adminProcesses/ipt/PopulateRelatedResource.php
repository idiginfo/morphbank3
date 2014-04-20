<?php
echo "truncate IptResourceRelationship" . $dataSetName . ";

insert into IptResourceRelationship" . $dataSetName . "
(occurrenceId, relationshipOfResource, relatedResourceId)
select occurrenceId, 'representedIn',concat ('http://www.morphbank.net/',id)
from IptOccurrence" . $dataSetName . ";

";

?>
