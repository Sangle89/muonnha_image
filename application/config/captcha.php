<?php
// BotDetect PHP Captcha configuration options

$config = array(
  // Captcha configuration for login page
  'LoginCaptcha' => array(
    'UserInputID' => 'CaptchaCode',
    'CodeLength' => CaptchaRandomization::GetRandomCodeLength(4, 6),
    'ImageStyle' => array(
      ImageStyle::Radar,
      ImageStyle::Collage,
      ImageStyle::Fingerprints,
    ),
  ),

  // Captcha configuration for forgot password page
  'ForgotPasswordCaptcha' => array(
    'UserInputID' => 'CaptchaCode',
    'CodeLength' => CaptchaRandomization::GetRandomCodeLength(3, 6),
    'CustomLightColor' => '#9966FF',
  ),

);