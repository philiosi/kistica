<?php

header("Location: /cps/cps.php");
exit;

print<<<EOS
<script>
document.location = "/cps/cps.php";
</script>
EOS;
exit;

?>
