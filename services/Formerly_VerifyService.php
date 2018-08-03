<?php
namespace Craft;

class Formerly_VerifyService extends BaseApplicationComponent
{
  public function verify($captchaResponse, $form)
  {
    $base = "https://www.google.com/recaptcha/api/siteverify";

    $params = array(
      'secret' =>  $form->reCaptchaSecretKey,
      'response' => $captchaResponse
    );

    $client = new \Guzzle\Http\Client();

    $request = $client->post($base);
    $request->addPostFields($params);
    $result = $client->send($request);

    if($result->getStatusCode() == 200)
    {
      $json = $result->json();
      if($json['success'])
      {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }
}
