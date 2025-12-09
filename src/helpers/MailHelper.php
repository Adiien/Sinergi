<?php
// File: src/helpers/MailHelper.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Memuat autoload composer
require_once __DIR__ . '/../../vendor/autoload.php';

class MailHelper
{
    /**
     * JANGAN HAPUS BARIS DI BAWAH INI
     * Ini adalah baris yang mendefinisikan variabel $toEmail, $toName, dan $token
     */
    public static function sendVerificationEmail($toEmail, $toName, $token)
    {
        $mail = new PHPMailer(true);

        try {
            // --- KONFIGURASI SERVER SMTP ---
            // Sesuaikan bagian ini dengan penyedia email Anda
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';           // Ganti dengan SMTP host Anda
            $mail->SMTPAuth   = true;
            $mail->Username   = 'farissinergi@gmail.com';     // Ganti dengan email pengirim
            $mail->Password   = 'wute osvo rcaa yjsm';        // Ganti dengan App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // --- PENGIRIM & PENERIMA ---
            $mail->setFrom('no-reply@sinergi.com', 'Admin SINERGI');

            // Variabel ini datang dari (..) di baris public static function di atas
            $mail->addAddress($toEmail, $toName);

            // --- KONTEN EMAIL ---
            $mail->isHTML(true);
            $mail->Subject = 'Verifikasi Akun SINERGI';

            // Pastikan BASE_URL sudah didefinisikan di index.php
            $verifyLink = BASE_URL . '/auth/verify?token=' . $token;

            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; color: #333;'>
                    <h2 style='color: #4F46E5;'>Selamat Datang di SINERGI!</h2>
                    <p>Halo <strong>$toName</strong>,</p>
                    <p>Terima kasih telah mendaftar. Silakan klik tombol di bawah ini untuk memverifikasi email Anda:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='$verifyLink' style='background-color: #4F46E5; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Verifikasi Email</a>
                    </p>
                    <p>Atau salin link ini: <br> $verifyLink</p>
                </div>
            ";

            $mail->AltBody = "Halo $toName, silakan verifikasi akun Anda di link: $verifyLink";

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error untuk debugging
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
    /**
     * Kirim Email Reset Password (OTP)
     */
    public static function sendResetCode($toEmail, $toName, $code)
    {
        loadEnv(__DIR__ . '/.env');

        $mail = new PHPMailer(true);

        try {
            // --- KONFIGURASI SERVER SMTP (Sama seperti sebelumnya) ---
            $mail->isSMTP();
            $mail->Host       = getenv('SMTP_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = getenv('SMTP_USERNAME'); // Sesuaikan
            $mail->Password   = getenv('SMTP_PASSWORD');    // Sesuaikan
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = getenv('SMTP_PORT');

            // --- PENGIRIM & PENERIMA ---
            $mail->setFrom(getenv('SMTP_FROM_EMAIL'), getenv('SMTP_FROM_NAME'));
            $mail->addAddress($toEmail, $toName);

            // --- KONTEN EMAIL ---
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Code - SINERGI';

            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; padding: 20px; text-align: center; color: #333;'>
                    <h2 style='color: #4F46E5;'>Permintaan Reset Password</h2>
                    <p>Gunakan kode berikut untuk mereset password akun Anda:</p>
                    <h1 style='font-size: 32px; letter-spacing: 5px; color: #333; margin: 20px 0;'>$code</h1>
                    <p>Kode ini berlaku selama 15 menit. Jangan berikan kode ini kepada siapapun.</p>
                </div>
            ";

            $mail->AltBody = "Kode reset password Anda adalah: $code";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $mail->ErrorInfo);
            return false;
        }
    }
}
