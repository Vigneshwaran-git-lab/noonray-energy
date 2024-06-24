<?php

use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;

require __DIR__ . '/vendor/autoload.php';

// your API token from here https://mailtrap.io/api-tokens
// $apiKey = getenv('74807a075777ba42c73f7240286eb8cc');
$apiKey = '74807a075777ba42c73f7240286eb8cc';
$mailtrap = new MailtrapClient(new Config($apiKey));

$email = (new Email())
  ->from(new Address('mailtrap@demomailtrap.com', 'Mailtrap Test'))
  ->to(new Address('noonray.web@outlook.com', 'Vigneshwaran Testing'))
  ->priority(Email::PRIORITY_HIGH)
  ->subject('Application for Content Writer')
  ->text("
    A new candidate has applied for the post Content Writer. Candidate's details are below: \n
    Name: {$firstName} {$lastName} \n
    Email Address: {$emailAddress} \n
    Phone Number: {$phoneNumber} \n
  ")
  // ->html(
  //   '<html>
  //       <body>
  //       <p><br>Hey</br>
  //       Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
  //       <p><a href="https://mailtrap.io/blog/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
  //       <img src="cid:logo">
  //       </body>
  //   </html>'
  // )
  ->embed(fopen('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg', 'r'), 'logo', 'image/svg+xml');

// Headers
$email->getHeaders()
  ->addTextHeader('X-Message-Source', 'domain.com')
  ->add(new UnstructuredHeader('X-Mailer', 'Mailtrap PHP Client')) // the same as addTextHeader
;

// Custom Variables
$email->getHeaders()
  ->add(new CustomVariableHeader('user_id', '45982'))
  ->add(new CustomVariableHeader('batch_id', 'PSJ-12'));

// Category (should be only one)
$email->getHeaders()
  ->add(new CategoryHeader('Integration Test'));

try {
  $response = $mailtrap->sending()->emails()->send($email); // Email sending API (real)

  var_dump(ResponseHelper::toArray($response)); // body (array)
} catch (Exception $e) {
  echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// OR send email to the Mailtrap SANDBOX

// try {
//   $response = $mailtrap->sandbox()->emails()->send($email, 1000001); // Required second param -> inbox_id

//   var_dump(ResponseHelper::toArray($response)); // body (array)
// } catch (Exception $e) {
//   echo 'Caught exception: ',  $e->getMessage(), "\n";
// }
