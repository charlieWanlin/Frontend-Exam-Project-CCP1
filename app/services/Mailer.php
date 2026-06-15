<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function sendVerification(string $toEmail, string $toName, string $token): bool
    {
        $baseUrl     = $_ENV['APP_URL'] ?? 'http://localhost:8888';
        $confirmLink = $baseUrl . '/auth/verify?token=' . urlencode($token);

        return self::send(
            to:      $toEmail,
            toName:  $toName,
            subject: 'Confirmez votre adresse e-mail — LE DRESSING',
            html:    self::templateVerification($toName, $confirmLink),
            text:    "Bonjour {$toName},\n\nConfirmez votre email :\n{$confirmLink}\n\nLe lien expire dans 24h.\n\n— LE DRESSING",
        );
    }

    public static function sendPasswordReset(string $toEmail, string $toName, string $token): bool
    {
        $baseUrl   = $_ENV['APP_URL'] ?? 'http://localhost:8888';
        $resetLink = $baseUrl . '/auth/reset?token=' . urlencode($token);

        return self::send(
            to:      $toEmail,
            toName:  $toName,
            subject: 'Réinitialisation de votre mot de passe — LE DRESSING',
            html:    self::templateReset($toName, $resetLink),
            text:    "Bonjour {$toName},\n\nRéinitialisez votre mot de passe :\n{$resetLink}\n\nLe lien expire dans 1h.\n\n— LE DRESSING",
        );
    }

    private static function send(
        string $to,
        string $toName,
        string $subject,
        string $html,
        string $text,
    ): bool {
        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAILTRAP_USERNAME'] ?? '';
            $mail->Password   = $_ENV['MAILTRAP_PASSWORD'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 2525;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@ledressing.fr',
                $_ENV['MAIL_FROM_NAME']    ?? 'LE DRESSING'
            );
            $mail->addAddress($to, $toName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = $text;

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log('[Mailer] Erreur : ' . $e->getMessage());
            return false;
        }
    }
    private static function templateVerification(string $name, string $link): string
    {
        return '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#F7F3EE;font-family:Helvetica Neue,Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F7F3EE;padding:40px 20px">
<tr><td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#fff;max-width:560px;width:100%">
<tr><td style="height:3px;background:#B8944B"></td></tr>
<tr><td style="padding:36px 40px 0;text-align:center">
  <p style="margin:0;font-family:Georgia,serif;font-size:11px;letter-spacing:.4em;text-transform:uppercase;color:#B8944B">LE DRESSING</p>
</td></tr>
<tr><td style="padding:32px 40px 40px">
  <p style="margin:0 0 8px;font-family:Georgia,serif;font-size:22px;font-weight:400;color:#111">Bienvenue, ' . htmlspecialchars($name) . '.</p>
  <p style="margin:0 0 28px;font-size:13px;color:#888;line-height:1.7">
    Votre compte LE DRESSING a bien été créé.<br>
    Il vous reste une étape : confirmer votre adresse e-mail.
  </p>
  <table cellpadding="0" cellspacing="0"><tr><td>
    <a href="' . $link . '" style="display:inline-block;background:#111;color:#fff;text-decoration:none;font-size:9px;letter-spacing:.22em;text-transform:uppercase;padding:14px 32px">
      Confirmer mon adresse &rarr;
    </a>
  </td></tr></table>
  <p style="margin:28px 0 0;font-size:11px;color:#aaa;line-height:1.7">
    Ce lien est valable <strong>24 heures</strong>.<br>
    Si vous n\'avez pas créé de compte, ignorez cet e-mail.
  </p>
</td></tr>
<tr><td style="padding:20px 40px;border-top:1px solid #f0ebe4">
  <p style="margin:0;font-size:10px;color:#ccc;text-align:center">© LE DRESSING — Votre style, vos références.</p>
</td></tr>
</table></td></tr></table>
</body></html>';
    }

    private static function templateReset(string $name, string $link): string
    {
        return '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#F7F3EE;font-family:Helvetica Neue,Arial,sans-serif">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#F7F3EE;padding:40px 20px">
<tr><td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#fff;max-width:560px;width:100%">
<tr><td style="height:3px;background:#B8944B"></td></tr>
<tr><td style="padding:36px 40px 0;text-align:center">
  <p style="margin:0;font-family:Georgia,serif;font-size:11px;letter-spacing:.4em;text-transform:uppercase;color:#B8944B">LE DRESSING</p>
</td></tr>
<tr><td style="padding:32px 40px 40px">
  <p style="margin:0 0 8px;font-family:Georgia,serif;font-size:22px;font-weight:400;color:#111">Réinitialisation<br>du mot de passe.</p>
  <p style="margin:0 0 28px;font-size:13px;color:#888;line-height:1.7">
    Bonjour ' . htmlspecialchars($name) . ',<br>
    Vous avez demandé à réinitialiser votre mot de passe.
  </p>
  <table cellpadding="0" cellspacing="0"><tr><td>
    <a href="' . $link . '" style="display:inline-block;background:#111;color:#fff;text-decoration:none;font-size:9px;letter-spacing:.22em;text-transform:uppercase;padding:14px 32px">
      Choisir un nouveau mot de passe &rarr;
    </a>
  </td></tr></table>
  <p style="margin:28px 0 0;font-size:11px;color:#aaa;line-height:1.7">
    Ce lien expire dans <strong>1 heure</strong>.<br>
    Si vous n\'avez pas fait cette demande, ignorez cet e-mail.
  </p>
</td></tr>
<tr><td style="padding:20px 40px;border-top:1px solid #f0ebe4">
  <p style="margin:0;font-size:10px;color:#ccc;text-align:center">© LE DRESSING — Votre style, vos références.</p>
</td></tr>
</table></td></tr></table>
</body></html>';
    }
}