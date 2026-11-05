<?php
// Réception du formulaire de demande d'intervention NuisiVox
// Envoie chaque demande valide à contact@nuisivox.fr, avec protections anti-spam.

date_default_timezone_set('Europe/Paris');
header('Content-Type: application/json; charset=utf-8');
header('X-Robots-Tag: noindex');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

const DESTINATAIRE = 'contact@nuisivox.fr';
const EXPEDITEUR   = 'contact@nuisivox.fr';
const DELAI_MIN    = 4;      // secondes minimum entre l'affichage et l'envoi (les robots sont instantanés)
const DELAI_MAX    = 86400;  // formulaire valable 24 h
const MAX_PAR_IP   = 5;      // demandes max par adresse IP...
const FENETRE_IP   = 3600;   // ...sur une heure
const MAX_GLOBAL   = 30;     // demandes max pour tout le site sur une heure (anti-inondation de la boîte mail)

function repondre($ok, $message, $code = 200) {
    http_response_code($code);
    echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

// Réponse « succès » silencieuse pour les robots détectés : ils ne savent pas qu'ils ont été bloqués.
function bloquer_robot() {
    repondre(true, 'Merci, votre demande a bien été envoyée.');
}

function nettoyer($v, $max) {
    $v = is_string($v) ? trim($v) : '';
    $v = str_replace(["\r", "\0"], '', $v);
    return mb_substr($v, 0, $max);
}

// Secret propre au serveur, créé au premier usage hors du dossier public (jamais dans le dépôt).
function secret() {
    foreach ([dirname(__DIR__), sys_get_temp_dir()] as $dossier) {
        $f = $dossier . '/.nuisivox-formulaire-secret';
        if (is_file($f) && strlen($s = (string)file_get_contents($f)) >= 32) return $s;
        if (is_writable($dossier)) {
            $s = bin2hex(random_bytes(32));
            if (@file_put_contents($f, $s, LOCK_EX) !== false) { @chmod($f, 0600); return $s; }
        }
    }
    repondre(false, "Le formulaire est indisponible. Merci de nous appeler au 06 60 68 78 19 ou de nous écrire à contact@nuisivox.fr.", 500);
}

// Jeton signé par le serveur à l'ouverture de la page : l'heure d'affichage ne peut pas être falsifiée.
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['jeton'])) {
    $t = (string)time();
    echo json_encode(['jeton' => $t . '.' . hash_hmac('sha256', $t, secret())]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    repondre(false, 'Méthode non autorisée.', 405);
}

// 1. Le formulaire doit venir du site lui-même (les navigateurs indiquent toujours l'origine)
$origine = $_SERVER['HTTP_ORIGIN'] ?? ($_SERVER['HTTP_REFERER'] ?? '');
if (!preg_match('#^https?://(www\.)?nuisivox\.fr(/|$)#i', $origine)) {
    repondre(false, 'Origine non autorisée. Merci de nous appeler au 06 60 68 78 19 ou de nous écrire à contact@nuisivox.fr.', 403);
}

// 2. Champ piège invisible : un humain le laisse vide, un robot le remplit
if (!empty($_POST['site_web'])) {
    bloquer_robot();
}

// 3. Jeton signé et délai de remplissage : trop rapide = robot
$jeton = explode('.', (string)($_POST['t'] ?? ''), 2);
if (count($jeton) !== 2 || !ctype_digit($jeton[0])
    || !hash_equals(hash_hmac('sha256', $jeton[0], secret()), $jeton[1])) {
    repondre(false, 'La page a expiré : merci de la recharger, puis de renvoyer votre demande.', 400);
}
$ecoule = time() - (int)$jeton[0];
if ($ecoule > DELAI_MAX) {
    repondre(false, 'La page a expiré : merci de la recharger, puis de renvoyer votre demande.', 400);
}
if ($ecoule < DELAI_MIN) {
    bloquer_robot();
}

// 4. Limite par adresse IP
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$fichier = sys_get_temp_dir() . '/nuisivox_form_' . hash('sha256', $ip . 'nuisivox');
$horodatages = [];
if (is_file($fichier)) {
    $horodatages = array_filter(
        array_map('intval', explode(',', (string)file_get_contents($fichier))),
        fn($t) => $t > time() - FENETRE_IP
    );
}
if (count($horodatages) >= MAX_PAR_IP) {
    repondre(false, 'Trop de demandes envoyées. Merci de nous appeler au 06 60 68 78 19 ou de nous écrire à contact@nuisivox.fr.', 429);
}
$fichier_global = sys_get_temp_dir() . '/nuisivox_form_global';
$global = [];
if (is_file($fichier_global)) {
    $global = array_filter(
        array_map('intval', explode(',', (string)file_get_contents($fichier_global))),
        fn($t) => $t > time() - FENETRE_IP
    );
}
if (count($global) >= MAX_GLOBAL) {
    repondre(false, 'Le formulaire est très sollicité. Merci de nous appeler au 06 60 68 78 19 ou de nous écrire à contact@nuisivox.fr.', 429);
}

// 5. Validation des champs
$nom      = nettoyer($_POST['nom'] ?? '', 80);
$tel      = nettoyer($_POST['tel'] ?? '', 30);
$commune  = nettoyer($_POST['commune'] ?? '', 80);
$nuisible = nettoyer($_POST['nuisible'] ?? '', 60);
$message  = nettoyer($_POST['message'] ?? '', 2000);

$nom = str_replace("\n", ' ', $nom);
$commune = str_replace("\n", ' ', $commune);

if ($nom === '' || $tel === '' || $commune === '') {
    repondre(false, 'Merci de renseigner votre nom, votre téléphone et votre commune.', 422);
}
$chiffres = preg_replace('/\D/', '', $tel);
if (strlen($chiffres) < 9 || strlen($chiffres) > 15 || !preg_match('/^[0-9 +().\-]+$/', $tel)) {
    repondre(false, 'Le numéro de téléphone ne semble pas valide.', 422);
}
$nuisibles_ok = ['Rats ou souris', 'Cafards', 'Punaises de lit', 'Frelons ou guêpes',
                 'Moustique tigre', 'Chenilles processionnaires', 'Autre ou je ne sais pas'];
if (!in_array($nuisible, $nuisibles_ok, true)) {
    $nuisible = 'Autre ou je ne sais pas';
}

// 6. Contenu typique de spam : liens, balises, caractères cyrilliques/chinois
$tout = $nom . ' ' . $commune . ' ' . $message;
if (preg_match_all('#https?://|www\.#i', $tout) > 1
    || preg_match('#<[a-z/][^>]*>|\[url#i', $tout)
    || preg_match('/[\x{0400}-\x{04FF}\x{4E00}-\x{9FFF}]/u', $tout)
    || preg_match('#https?://|www\.#i', $nom . $commune)) {
    bloquer_robot();
}

// 7. Envoi
$sujet = 'Demande NuisiVox : ' . $nuisible . ' à ' . $commune;
$corps = "Nouvelle demande depuis nuisivox.fr\n\n"
       . "Nom : $nom\n"
       . "Téléphone : $tel\n"
       . "Commune : $commune\n"
       . "Nuisible : $nuisible\n\n"
       . "Ce que le client constate :\n" . ($message !== '' ? $message : '(rien de précisé)') . "\n\n"
       . "Envoyé le " . date('d/m/Y à H:i') . "\n";

$entetes = 'From: NuisiVox <' . EXPEDITEUR . ">\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "Content-Transfer-Encoding: 8bit\r\n"
         . 'X-Mailer: nuisivox.fr';

$envoye = mail(
    DESTINATAIRE,
    '=?UTF-8?B?' . base64_encode($sujet) . '?=',
    $corps,
    $entetes,
    '-f' . EXPEDITEUR
);

if (!$envoye) {
    repondre(false, "L'envoi a échoué. Merci de nous appeler au 06 60 68 78 19 ou de nous écrire à contact@nuisivox.fr.", 500);
}

$horodatages[] = time();
@file_put_contents($fichier, implode(',', $horodatages), LOCK_EX);
$global[] = time();
@file_put_contents($fichier_global, implode(',', $global), LOCK_EX);

repondre(true, 'Merci, votre demande a bien été envoyée. Nous vous rappelons rapidement.');
