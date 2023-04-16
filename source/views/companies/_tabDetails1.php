<?php
/*
 * Dane firmy w TbTabs
 */
?>
<div class="details">
	<span class="detail-row">
	   	<span class="detail-label">Nazwa firmy:</span>
	   	<span class="detail-value"><?php echo $item->name; ?></span>
	</span>	
	<span class="detail-row">
	   	<span class="detail-label">NIP:</span>
	   	<span class="detail-value"><?php echo $company->nip; ?></span>
	</span>
	<span class="detail-row">
	   	<span class="detail-label">REGON:</span>
	   	<span class="detail-value"><?php echo $company->regon; ?></span>
	</span>
	<span class="detail-row">
	   	<span class="detail-label">Numer KRS:</span>
	   	<span class="detail-value"><?php echo $company->krs; ?></span>
	</span>
</div>