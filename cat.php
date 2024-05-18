<?php
include("ApiGoGo.php");

//display fun fact about cats
$catRandomFact = new CatApi("https://cat-fact.herokuapp.com");
$fact = json_decode($catRandomFact->getRandom('cat', 1));
echo $fact->text, PHP_EOL;
//ask for name and email
$name = readline("Enter Your Name: ");
$email = readline("Enter Your Email: ");

//agify
$agify = new Agify("https://api.agify.io", $name);
$results = json_decode($agify->getAgify());
if(isset($results->error)){
    echo $results->error;
} else {
    echo "Name $results->name is $results->age years old. $results->count people have this name.", PHP_EOL;
}
//genderize
$genderize = new Agify("https://api.genderize.io", $name);
$results = json_decode($genderize->getAgify());
if(isset($results->error)){
    echo $results->error;
} else {
    echo "It is " .
        $results->probability * 100 .
        "% $results->gender name.", PHP_EOL;
}
//nationalize
$nationalize  = new Agify("https://api.nationalize.io", $name);
$results = json_decode($nationalize->getAgify());
if(isset($results->error)){
    echo $results->error;
} else {
    echo "Name nationality";
    foreach ($results->country as $country) {
        echo " | " . $country->country_id . " " . number_format($country->probability * 100, 2)   . "%";
    }
    echo PHP_EOL;
}
//validate email
$validateEmail = new EmailValidation("https://api.emailvalidation.io/",
    $email, "ema_live_mkpISv6DCpA4Qd9LaxDmkDj2n6DixAUYML6c3Rwq");
$result =  json_decode($validateEmail->validate());
echo "Email validation score: $result->score $result->reason State: $result->state", PHP_EOL;
//send email if valid
if($result->state === "deliverable") {
    sendEmailNotification('CatApp.io', 'Hello World', $fact->text, $email);
}