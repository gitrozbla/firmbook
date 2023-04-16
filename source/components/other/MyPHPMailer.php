<?php
/**
 * Wysyłanie maili.
 *
 * Dodaje funkcjonalności do wrappera Yii PHPMailer.
 * @see http://www.yiiframework.com/extension/phpmailer/
 * 
 * @category components
 * @package components\other
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class MyPHPMailer extends PHPMailer {

    // PATCHED FUNCTION
    /**
     * Kopia funkcji z PHPMailer.
     * Zawiera łatkę naprawiającą błąd przy wysyłaniu
     * zagnieżdzonych w html zdjęć jako załącznik.
     * Skopiowana, aby nie powodowało problemów przy zmianie wersji PHPMailer.
     * @param string $disposition_type
     * @param string $boundary
     * @return string
     */
    protected function AttachAll($disposition_type, $boundary) {
        // Return text of body
        $mime = array();
        $cidUniq = array();
        $incl = array();

        // Add all attachments
        foreach ($this->attachment as $attachment) {
            // CHECK IF IT IS A VALID DISPOSITION_FILTER
            if ($attachment[6] == $disposition_type) {
                // Check for string attachment
                $string = '';
                $path = '';
                $bString = $attachment[5];
                if ($bString) {
                    $string = $attachment[0];
                } else {
                    $path = $attachment[0];
                }

                $inclhash = md5(serialize($attachment));
                if (in_array($inclhash, $incl)) {
                    continue;
                }
                $incl[] = $inclhash;
                $filename = $attachment[1];
                $name = $attachment[2];
                $encoding = $attachment[3];
                $type = $attachment[4];
                $disposition = $attachment[6];
                $cid = $attachment[7];
                if ($disposition == 'inline' && isset($cidUniq[$cid])) {
                    continue;
                }
                $cidUniq[$cid] = true;

                $mime[] = sprintf("--%s%s", $boundary, $this->LE);
                // BEGIN PATCH (force inline)
                if ($disposition == 'inline') {
                    $pathinfo = pathinfo($filename);
                    $mime[] = sprintf("Content-Type: image/" . $pathinfo['extension'] . ";\n");
                } else {
                    $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $this->EncodeHeader($this->SecureHeader($name)), $this->LE);
                }
                // END PATCH
                // $mime[] = sprintf("Content-Type: %s; name=\"%s\"%s", $type, $this->EncodeHeader($this->SecureHeader($name)), $this->LE);
                $mime[] = sprintf("Content-Transfer-Encoding: %s%s", $encoding, $this->LE);

                if ($disposition == 'inline') {
                    $mime[] = sprintf("Content-ID: <%s>%s", $cid, $this->LE);
                }

                $mime[] = sprintf("Content-Disposition: %s; filename=\"%s\"%s", $disposition, $this->EncodeHeader($this->SecureHeader($name)), $this->LE . $this->LE);

                // Encode as string attachment
                if ($bString) {
                    $mime[] = $this->EncodeString($string, $encoding);
                    if ($this->IsError()) {
                        return '';
                    }
                    $mime[] = $this->LE . $this->LE;
                } else {
                    $mime[] = $this->EncodeFile($path, $encoding);
                    if ($this->IsError()) {
                        return '';
                    }
                    $mime[] = $this->LE . $this->LE;
                }
            }
        }

        $mime[] = sprintf("--%s--%s", $boundary, $this->LE);

        return implode("", $mime);
    }

}
