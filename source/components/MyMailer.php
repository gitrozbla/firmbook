<?php

Yii::import('ext.mailer.EMailer', true);
Yii::import('application.components.other.MyPHPMailer', true);

/**
 * Wrapper dla PHPMailer.
 * 
 * @see http://phpmailer.worxware.com/index.php?pg=properties
 * Rozszerza funkcjonalności dodatku EMailer.
 * @see http://www.yiiframework.com/extension/mailer/
 * 
 * @category components
 * @package components
 * @author BAI
 * @copyright (C) 2014 BAI
 */
class MyMailer extends EMailer 
{
    /**
     * Domyślny nadawca (email).
     * @var string|null
     */
    public $FromDefault;
    /**
     * Domyślna nazwa adresata.
     * @var string|null
     */
    public $FromNameDefault;
    
    // requires _myMailer to be protected
    /**
     * Konstruktor.
     * Wymaga zmiany widoczności EMailer::_myMailer na protected.
     */
    public function __construct()
    {
        $this->_myMailer = new MyPHPMailer();
    }
    
    /**
     * Wrapper funkcji _call.
     * @param string $method Funkcja.
     * @param array $params Parametry.
     * @return mixed Zwrócona wartość przez wywoływaną funkcję.
     * @throws CException
     */
    public function __call($method, $params)
    {
        if (is_object($this->_myMailer) && get_class($this->_myMailer)==='MyPHPMailer') return call_user_func_array(array($this->_myMailer, $method), $params);
        else throw new CException(Yii::t('EMailer', 'Can not call a method of a non existent object'));
    }

    /**
     * Wrapper funkcji _set.
     * @param string $name Nazwa atrybutu.
     * @param mixed $value Wartość atrybutu.
     * @throws CException
     */
    public function __set($name, $value)
    {
       if (is_object($this->_myMailer) && get_class($this->_myMailer)==='MyPHPMailer') $this->_myMailer->$name = $value;
       else throw new CException(Yii::t('EMailer', 'Can not set a property of a non existent object'));
    }

    /**
     * Wrapper funkcji _get.
     * @param string $name Nazwa atrybutu
     * @return mixed Wartość atrybutu.
     * @throws CException
     */
    public function __get($name)
    {
       if (is_object($this->_myMailer) && get_class($this->_myMailer)==='MyPHPMailer') return $this->_myMailer->$name;
       else throw new CException(Yii::t('EMailer', 'Can not access a property of a non existent object'));
    }

    
    /**
     * Wysyła wiadomość systemową.
     * Nadawcą jest system, a treść jest wewnątrz specjalnego layoutu.
     * Parametry zgodnie z PHPMailer.
     * @see http://phpmailer.worxware.com/index.php?pg=properties
     * @param string $to Adresat.
     * @param string $subject Temat.
     * @param string $message Wiadomość.
     * @param string|null $replyTo Adres zwrotny.
     * @param boolean $smtpClose Czy zamknąć połączenie z serwerem po wysłaniu wiadomości.
     * @param string|array|boolean $bcc Ukryty adresat, adresaci lub false gdy brak.
     * @throws CHttpException
     */
    public function systemMail($to, $subject, $message, $replyTo=null, $smtpClose=true, $bcc=false) {
        
        ob_start();
        
        try{
            $this->clearAll();

			if (!is_array($to)) {
				$to = explode(',', $to);
			}
			foreach($to as $toAddress) {
				$toAddress = trim($toAddress);
				if (!empty($toAddress)) $this->AddAddress($toAddress);
			}
            $this->Subject = Yii::app()->name.' - '.$subject;
            $this->Body    = $message;

            if ($bcc) {
                if (is_string($bcc)) {
                    $this->AddBCC($bcc);
                } else {
                    foreach($bcc as $bccEmail) {
                        $this->AddBCC($bccEmail);
                    }
                }
            }

            if ($replyTo !== null) {
                $this->AddReplyTo($replyTo['email'], $replyTo['name']);
            } else {
                $this->AddReplyTo(Yii::app()->params['branding']['email'], Yii::app()->name);
            }
            $this->SetFrom($this->FromDefault, $this->FromNameDefault);

            $altBody = trim(preg_replace('/\s+/', ' ', $message));          // \n -> ' '
            $altBody = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $altBody);  // br -> \n
            $altBody = strip_tags($altBody);                                // html -> ''
            $altBody = preg_replace('/[ \t\r]+/u', ' ', $altBody);          // '   ' -> ' '
            $this->AltBody = $altBody;

            $this->Send();
            if ($smtpClose) {
                $this->SmtpClose();
            }
            
        } catch (phpmailerException $e) {
            $debugData = ob_get_contents();
            ob_end_clean();
            Yii::log($debugData, 'info', 'email');
            throw new CHttpException(500, $e->errorMessage());
        } catch (Exception $e) {
            $debugData = ob_get_contents();
            ob_end_clean();
            Yii::log($debugData, 'info', 'email');
            throw new CHttpException(500, $e->getMessage());
        }
        
    }
    
    /**
     * Resetuje konfigurację wysyłanej wiadomości.
     */
    public function clearAll() {
        $this->ClearAddresses();
        $this->ClearCCs();
        $this->ClearBCCs();
        $this->ClearReplyTos();
        $this->ClearAllRecipients();
        //$this->ClearAttachments();
        $this->ClearCustomHeaders();
    }
    
}
