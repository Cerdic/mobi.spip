<?php

/**
 * Mobile Detect
 *
 * @author     vic.stanciu - http://code.google.com/u/vic.stanciu
 * @version    2010-08-30 15:17 MobileDetect.class.php updated by bkuberek http://github.com/bkuberek
 * @todo       Make better Device Identification
 *
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * Complete User_Agent string :
 * http://www.zytrax.com/tech/web/mobile_ids.html
 *
 * example:
 *         $detect = MobileDetect::getInstance();
 *         if ($detect->isMobile()) { ... }
 *         if ($detect->isIphone()) { ... }
 *         ...etc.
 */
class MobileDetect {

  /**
   * @var MobileDetect
   */
  static protected $instance;

  /**
   * @var boolean
   */
  protected $accept;

  /**
   * @var boolean
   */
  protected $userAgent;

  /**
   * @var string
   */
  protected $device;

  /**
   * @var array
   */
  protected $is           = array();

	/**
	 * @var boolean
	 */
  protected $isMobile     = false;


  /**
   * Available devices and their respective matching pattern
   *
   * @var array [device] => pattern
   */
  protected $devices      = array(
      "android"    => "android",
      "blackberry" => "blackberry",
      "iphone"     => "(iphone|ipod)",
	    "ipad"       => "ipad",
      "opera"      => "opera (mini|mobi)",
      "palm"       => "(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)",
      "windows"    => "windows ce; (iemobile|ppc|smartphone)",
      "generic"    => "(kindle|mobile|mmp|midp|o2|pda|pocket|psp|symbian|smartphone|treo|up.browser|up.link|vodafone|wap)"
  );

  /**
   * Constructor - checks request headers hydrate properties
   */
  protected function __construct() {
    $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
    $this->accept    = $_SERVER['HTTP_ACCEPT'];

    if (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
      $this->isMobile = true;
    } elseif (strpos($this->accept, 'text/vnd.wap.wml') > 0 || strpos($this->accept, 'application/vnd.wap.xhtml+xml') > 0) {
      $this->isMobile = true;
    } else {
      foreach ($this->devices as $device => $regexp) {
        if ($this->isDevice($device)) {
          $this->isMobile = true;
          $this->device = $device;
          break;
        }
      }
    }
  }

  /**
   * Returns true if the detected device name matches the given {$device} parameter
   *
   * @param string $device
   * @return bool
   */
  protected function isDevice($device) {
    $var = "is".ucfirst($device);
    $device = strtolower($device);
    $return = (isset($this->is[$device]) ? $this->is[$device] : (bool) preg_match("/".$this->devices[$device]."/i", $this->userAgent));
    if ($device != 'generic' && $return == true) {
      $this->is['generic'] = false;
    }
    return $return;
  }

  /**
   * Returns true if any type of mobile device detected, including special ones
   * @return bool
   */
  public function isMobile() {
    return $this->isMobile;
  }

  /**
   * Get the current device
   *
   * @todo make better device identification
   * @return string
   */
  public function getDevice() {
    return $this->device;
  }

  /**
   * Overloads isAndroid() | isBlackberry() | isOpera() | isPalm() | isWindows() | isGeneric()
   * or every isXxxx() where xxxx is a known device in $devices
   * through isDevice()
   *
   * @param string $name
   * @param array $arguments
   * @return bool
   */
  public function __call($name, $arguments) {
    $device = substr($name, 2);
    if ($name == "is".ucfirst($device)) {
      return $this->isDevice($device);
    } else {
      trigger_error("Method $name not defined", E_USER_ERROR);
    }
  }

  /**
   * Disable cloning
   */
  protected function __clone() {
    trigger_error("Cannot clone a singleton class", E_USER_ERROR);
  }

  /**
   * Retrieve the MobileDetect object
   *
   * @return MobileDetect
   */
  static public function getInstance() {
    if (!(self::$instance instanceof self)) {
      self::$instance = new self;
    }
    return self::$instance;
  }
}