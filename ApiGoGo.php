<?php
//dependencies
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ApiGoGo
{
    protected string $apikey;
    protected string $url;
    public function __construct(string $url, string $apikey = "")
    {
        $this->apikey = $apikey;
        $this->url = $url;
    }
    protected function getRequest(string $url): string
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        if( ! $result = curl_exec($request))
        {
            trigger_error(curl_error($request));
        }
        curl_close($request);
        return $result;
    }
    protected function postRequest(string $url, array $data): string
    {
        $request = curl_init();
        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        if( ! $result = curl_exec($request))
        {
            trigger_error(curl_error($request));
        }
        curl_close($request);
        return $result;
    }

}
class CatApi extends ApiGoGo
{
    public function getRandom(string $type, int $amount): string {
        $endpoint = "$this->url/facts/random?animal_type=$type&amount=$amount";
        return ApiGoGo::getRequest($endpoint);
    }
}
class Agify extends ApiGoGo
{
    public string $name;
    public function __construct(string $url, string $name, string $apikey = "")
    {
        parent::__construct($url, $apikey);
        $this->name = $name;
    }
    public function getAgify(): string
    {
        $endpoint = "$this->url?name=$this->name";
        return ApiGoGo::getRequest($endpoint);
    }
}
class EmailValidation extends ApiGoGo
{
    public string $email;
    public function __construct(string $url, string $email, string $apikey = "") {
        parent::__construct($url, $apikey);
        $this->email = $email;
    }
    public function validate(): string
    {
        $endpoint = "$this->url/v1/info?email=$this->email&apikey=$this->apikey";
        return ApiGoGo::getRequest($endpoint);
    }
}
class ShortenUri extends ApiGoGo
{
    public function __construct(string $url)
    {
        parent::__construct($url);
    }
    public function shortenUri(string $uri): string
    {
        $data = [
            'url' => $uri,
        ];
        return ApiGoGo::postRequest($this->url, $data);
    }
}

function sendEmailNotification(string $from, string $subject, string $content, string $recipient): void {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        //$mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'mail.inbox.lv';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ambax.io@inbox.lv';
        $mail->Password   = 'JapsTQ786R'; //here pass
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        // Recipients
        $mail->setFrom('ambax.io@inbox.lv', $from);
        $mail->addAddress($recipient); //here the address to.

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}