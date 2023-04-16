<?php
/**
 * Wiadomoœæ email dla formularza kontaktu.
 *
 * @category views
 * @package main
 * @author
 * @copyright (C)
 */
?>

<h2><?php echo $email->subject; ?></h2>
<p>
	<?php echo nl2br($email->message); ?><br />
</p>
