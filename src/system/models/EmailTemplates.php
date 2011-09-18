<?php
/**
 * Email Template model.
 *
 * Renders email template files and returns Zend_Mail.
 *
 * @package Simplecart
 * @subpackage Mail
 * @author spekkionu
 * @uses Zend_Mail
 * @uses Zend_View
 * @uses Zend_Layout
 */
class EmailTemplates
{

  /**
   * Template directory
   * @var array $template_dir
   */
  private static $template_dir;

  /**
   * Email config
   * @var Zend_Config $config
   */
  private static $config;

  /**
   * @var Zend_View $view
   */
  private $view;

  /**
   * @var Zend_Layout $layout
   */
  private $layout;

  /**
   * Sets template directory
   * @param string $dir
   */
  public static function setTemplateDir($dir) {
    if (!is_dir($dir)) {
      throw new Exception("Template directory not found.");
    }
    self::$template_dir = realpath($dir);
  }

  /**
   * Sets email config
   * @param Zend_Config $config
   */
  public static function setConfig(Zend_Config $config) {
    self::$config = $config;
  }

  /**
   * Password Reset email
   * Sends token and pin to user email address
   * @param array $user
   * @return Zend_Mail
   */
  public function passwordReset(array $user) {
    $this->setupView();
    $this->view->user = $user;
    $mailbody = $this->layout->render('password-reset');
    $mail = $this->getDefaultMailInstance();
    $mail->setSubject("Password reset requested");
    $mail->setBodyHtml($mailbody, 'UTF-8', Zend_Mime::ENCODING_BASE64);
    // Unlike most emails, this one should still be sent to user rather than test address
    $mail->addTo($user['email'], trim($user['firstname'] . ' ' . $user['lastname']));
    return $mail;
  }

  /**
   * Setup
   */
  private function setupView() {
    if (!self::$template_dir) {
      throw new Exception("Must first set template directory.");
    }
    $view = new Zend_View();
    $view->setScriptPath(self::$template_dir);
    $layout = new Zend_Layout();
    $layout->setLayoutPath(self::$template_dir . '/layout');
    $layout->setView($view);
    $this->view = $view;
    $this->layout = $layout;
  }

  /**
   * Setup common mail setting that are used for most emails
   * @return Zend_Mail
   */
  public function getDefaultMailInstance() {
    $mail = new Zend_Mail('UTF-8');
    $mail->setFrom(self::$config->from->email, self::$config->from->name);
    $mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
    if (self::$config->bcc->enabled) {
      foreach (self::$config->bcc->address as $email) {
        $mail->addBcc($email);
      }
    }
    return $mail;
  }

}