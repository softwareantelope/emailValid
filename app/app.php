<?php 

require_once __DIR__."/boostrap.php";

$app = new Silex\Application();

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Message; 

$app['debug'] = true;
$app->register(new Silex\Provider\SwiftmailerServiceProvider());

$app->get('/', function() {
    $output = Message::welcome();
    return new Response($output, 200);
});

$app->get('/send', function() use ($app) {
    return $app->redirect('/');
});

$app->get('/valid', function () {
    $output = Message::messageTo();
    return new Response($output, 200);
});

$app->post('/send', function(Request $request) use ($app) {
    // validate email is valid
    $email = $request->get('email');
    $known_secret = md5('this is a known secret required for verification');

    $link = 'https://securelink.example.com?data='.base64_encode($email . '|||' . $known_secret);

    $messageBody = Message::getReply($email, $link);

    $message = \Swift_Message::newInstance()
        ->setSubject('Email Address Verification')
        ->setFrom(array('noreply@sfsw.net'))
        ->setTo(array($email))
        ->setBody($messageBody);
    $app['mailer']->send($message);

    $output = `cat ../resource/views/postEmailAddress.html`;
    $output = preg_replace('/%%email%%/', $email, $output);

    return new Response($output, 200); 
});

return $app;
