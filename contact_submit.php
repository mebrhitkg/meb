<?php
require_once __DIR__.'/db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
  $subject = filter_var($_POST['subject'] ?? '', FILTER_SANITIZE_STRING);
  $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);

  if(!$email || !$name || !$subject || !$message){
    header('Location: ../contact.html?error=invalid');
    exit;
  }

  try{
    $pdo = getPDO();
    $stmt = $pdo->prepare('INSERT INTO contact_messages (name,email,subject,message,created_at) VALUES (?,?,?,?,NOW())');
    $stmt->execute([$name,$email,$subject,$message]);
    header('Location: ../contact.html?success=sent');
  } catch(Exception $e){
    header('Location: ../contact.html?error=db');
  }
  exit;
}
