<?php

function get($s, $d = ""): string
{
  $v = isset($_POST[$s]) ? htmlspecialchars($_POST[$s]) : $d;
  return empty($v) ? $d : $v;
}

function get_date($s): string
{
  $value = get($s);
  if(!$value)
  {
    return "[keine Angabe]";
  }
  try {
    $date = DateTime::createFromFormat("Y-m-d", $value);
    if($date) {
      return $date->format("d.m.Y");
    }
  } catch (Exception $e) {
  }
  return $value;
}

$name = get("vorname", "[keine Angabe]")." ".get("nachname", "[keine Angabe]");
$email = get("email", "[keine Angabe]");
$bungalow = get("bungalow");
$telefon = get("telefon", "[keine Angabe]");
$strasse = get("strasse", "[keine Angabe]");
$postleitzahl = get("postleitzahl", "[keine Angabe]");
$ort = get("ort", "[keine Angabe]");
$personen = get("personen", "[keine Angabe]");
$von = get_date("von");
$bis = get_date("bis");
$nachricht = get("nachricht");
$spam = get("checkbox") == "yes" || get("uncheckbox") != "yes";

if(!$spam)
{
  if(empty($bungalow))
  {
    $subject = "Nachricht von ".$name;
    $body = $name." (".$email." / ".$telefon.") hat eine Nachricht geschickt.

Nachricht:
".$nachricht;
  }
  else
  {
    $subject = "Anfrage von ".$name;
    $body = $name." (".$email.") hat eine Anfrage für den
Zeitraum vom ".$von." bis ".$bis." im ".$bungalow." (für ".$personen." Personen) geschickt.

Adresse:
".$strasse."
".$postleitzahl." ".$ort."

Telefon:
".$telefon."

Nachricht:
".$nachricht;
  }

  $to = "info@ostseedatsche.de";
  $headers = "From: info@ostseedatsche.de" . "\r\n" .
      "Reply-To: ". $email ."\r\n" .
      "X-Mailer: PHP/" . phpversion() ."\r\n";

  mail($to, $subject, $body, $headers);
}

header("Location: https://www.ostseedatsche.de/gesendet");
exit();
?>
