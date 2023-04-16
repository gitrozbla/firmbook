<?php
/*
 * Dane firmy w TbTabs
 */
?>
<div class="details">
    <span class="detail-row">
    	<span class="detail-label">Ulica:</span>
    	<span class="detail-value"><?php echo $company->street; ?></span>
    </span>	
    <span class="detail-row">
    	<span class="detail-label">Miasto:</span>
    	<span class="detail-value"><?php echo $company->city; ?></span>
    </span>
    <span class="detail-row">
    	<span class="detail-label">Województwo:</span>
    	<span class="detail-value"><?php echo $company->province; ?></span>
    </span>
    <span class="detail-row">
    	<span class="detail-label">Kod pocztowy:</span>
    	<span class="detail-value"><?php echo $company->postcode; ?></span>
   </span>
</div>