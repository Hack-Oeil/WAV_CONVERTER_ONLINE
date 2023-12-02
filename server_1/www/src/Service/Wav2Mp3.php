<?php
namespace App\Service;


class Wav2Mp3 {

    private array $file;

    public function __construct(array $file) {
        $this->file = $file;
    }

    public function uploadFile() {
        if($this->controlFileWav($this->file)) {
            if (!move_uploaded_file($this->file['tmp_name'], ROOT_DIR.'/tmp/'.$this->file['name'])) 
            {   
                throw new \Exception("Une erreur est survenue lors du téléchargement du fichier.");
            }
        }
        return $this;
    }


    private function controlFileWav($file) {
        $tmpFile = $file['tmp_name'];
        $filename = $file['name'];
        $newFilename = '';

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $type_mime = finfo_file($finfo, $tmpFile);
        finfo_close($finfo);
        if(stripos($filename, $_ENV['DEFAULT_CTF_FLAG']) !== false) {
            throw new \Exception("Tentative de triche détecté.");
        }
        // Vérification du type MIME pour déterminer s'il s'agit d'un fichier WAV
        if ($type_mime === 'audio/wav' || $type_mime === 'audio/x-wav') {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            // Vérification de l'extension
            if (strtolower($extension) === 'wav') {
                return true;
            } else {
                throw new \Exception("Le fichier n'a pas l'extension WAV.");
            }
        } else {
            throw new \Exception("Le fichier n'est pas un fichier WAV.");
        }
    }

    public function convert() {
        $input =  ROOT_DIR.'/tmp/'.$this->file['name'];
        $output =  ROOT_DIR.'/tmp/output.mp3';

        $cmd = "lame --preset standard ".$input." ".$output." 2>&1";
        exec($cmd, $resultCmd, $error);

        $result = '';
        
        foreach ($resultCmd as $line) $result .= $line."\n";

        if(file_exists($output)) {
            $newFilename = md5_file($output).".mp3";
            $destination = ROOT_DIR."/public/download/".$newFilename;
            copy($output, $destination);
            if(file_exists($destination)) {
                return [
                    'file'  => $newFilename,
                    'debug' => $result
                ];
            }                     
        }
        
        if(empty($result)) {
            throw new \Exception("Erreur de commande, l'instruction reçu n'est pas correcte"); 
        }

        return [
            'file'  => null,
            'debug' => $result
        ];
    }
}