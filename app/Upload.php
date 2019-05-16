<?php
/**
 * Created by PhpStorm.
 * User: Simona
 * Date: 16/05/2019
 * Time: 19:16
 */

class Upload{
    const STORAGE_ACCOUNT = 'csb5cbbdcb8b99bx40f4xaba';
    const CONTAINER_NAME  = 'media';
    const ACCESS_KEY      = 'CoNviqBdLZTX4l7CgLE6ZZXcUooBrCKfW3bS5CMm6nZNnXoOhjBsMkC5xOwByIQzLRHZjKzOyGTwCV0TkPHCqQ==';

    protected $file;
    protected $fileLen;
    protected $blobName;
    protected $destinationURL;
    protected $access;

    public function __construct($file)
    {
        $this->file     = $file;
        $this->fileLen  = filesize($this->file);
        $this->blobName = basename($file);
        $encodedKey     = file_get_contents('../storage/access.txt');
        $this->access   = base64_decode($encodedKey);
    }

    public function uploadBlob() {

        $this->destinationURL = "https://" . self::STORAGE_ACCOUNT . '.blob.core.windows.net/' . self::CONTAINER_NAME . "/$this->blobName";

        $currentDate = gmdate("D, d M Y H:i:s T", time());

        $headerResource = "x-ms-blob-cache-control:max-age=3600\nx-ms-blob-type:BlockBlob\nx-ms-date:$currentDate\nx-ms-version:2015-12-11";
        $urlResource    = "/" . self::STORAGE_ACCOUNT ."/" . self::CONTAINER_NAME . "/$this->blobName";

        $arraysign = $this->getSigns($headerResource, $urlResource);
        $str2sign  = implode("\n", $arraysign);

        $sig = base64_encode(hash_hmac('sha256', urldecode($str2sign), base64_decode($this->access), true));
        $authHeader = "SharedKey " . self::STORAGE_ACCOUNT . ":$sig";

        $headers = $this->getHeaders($authHeader, $currentDate);

        return $this->runRequest($headers);
    }

    function runRequest($headers)
    {
        $handle  = fopen($this->file, "r");

        $ch = curl_init($this->destinationURL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_INFILE, $handle);
        curl_setopt($ch, CURLOPT_INFILESIZE, $this->fileLen);
        curl_setopt($ch, CURLOPT_UPLOAD, true);
        $result = curl_exec($ch);

        $error = (curl_error($ch));

        curl_close($ch);

        if(empty($result) && empty($error)){
            return ['success' => "The file was successfully uploaded. File link: " . $this->destinationURL];
        }
        return ['result' => $result, 'error' => $error];
    }

    function getSigns($headerResource,$urlResource){

        $arraysign = [];
        $arraysign[] = 'PUT';               /*HTTP Verb*/
        $arraysign[] = '';                  /*Content-Encoding*/
        $arraysign[] = '';                  /*Content-Language*/
        $arraysign[] = $this->fileLen;            /*Content-Length (include value when zero)*/
        $arraysign[] = '';                  /*Content-MD5*/
        $arraysign[] = 'image/jpg';         /*Content-Type*/
        $arraysign[] = '';                  /*Date*/
        $arraysign[] = '';                  /*If-Modified-Since */
        $arraysign[] = '';                  /*If-Match*/
        $arraysign[] = '';                  /*If-None-Match*/
        $arraysign[] = '';                  /*If-Unmodified-Since*/
        $arraysign[] = '';                  /*Range*/
        $arraysign[] = $headerResource;     /*CanonicalizedHeaders*/
        $arraysign[] = $urlResource;        /*CanonicalizedResource*/
        return $arraysign;
    }

    protected function getHeaders($authHeader, $currentDate)
    {
        return [
            'Authorization: ' . $authHeader,
            'x-ms-blob-cache-control: max-age=3600',
            'x-ms-blob-type: BlockBlob',
            'x-ms-date: ' . $currentDate,
            'x-ms-version: 2015-12-11',
            'Content-Type: image/jpg',
            'Content-Length: ' . $this->fileLen
        ];
    }

}



