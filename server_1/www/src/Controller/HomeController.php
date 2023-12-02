<?php
 
namespace App\Controller;

use Yoop\AbstractController;
use App\Service\Wav2Mp3;

class HomeController extends AbstractController
{
    public function print() 
    {
        $debug = null;
        $file = null;
        $error = null;
        try {
            if(!empty($_FILES) && isset($_FILES['fichier_wav']) 
                && $_FILES['fichier_wav']['error'] === UPLOAD_ERR_OK) 
            {
                $serviceConvert = new Wav2Mp3($_FILES['fichier_wav']);
                $result = $serviceConvert->uploadFile()->convert();
                $result = str_replace($_ENV['DEFAULT_CTF_FLAG'], base64_decode('QmllbiBqb3XDqSBsZSBmbGFnIGVzdCA6IA').$this->getFlag(), $result);
                if($result !== false) {
                    $file = $result['file'];
                    $debug = $result['debug'];
                } else {
                    $error = "Une erreur est survene et la conversion n'a pas aboutie.";
                }
            }
        } catch(\Exception $e) {
            $error = nl2br($e->getMessage());
        } finally {
            // Si on recoit un fichier .wav
            return $this->render('web/home', [
                'error' => $error,
                'file'  => $file,
                'debug' => $debug
            ]);
        }
    }
}
