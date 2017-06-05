<?php

namespace BOI_CI\Service;

use Drupal\Driver\Exception\Exception;

class Rsync extends Shell {
  private $rsync;
  private $options;
  private $flags = '-vr';
  private $source;
  private $destination;

  public function __construct() {
    parent::__construct();
    $this->rsync = trim($this->execute("which rsync"));

    // Make sure git is installed and available.
    if (empty($this->rsync)) {
      throw new \Exception('Rsync not found');
    }
  }

  /**
   * Executes the rsync command based on class properties.
   * @return string
   * @throws \Exception
   */
  public function sync() {
    $options = !empty($this->options) ? implode(" ", $this->options) : "";

    if (empty($this->source)) {
      throw new \Exception('No source specified for rsync');
    }

    if (empty($this->destination)) {
      throw new \Exception('No destination specified for rsync');
    }

    return $this->execute("$this->rsync $this->flags $this->source $this->destination $options");
  }

  /**
   * The source to copy from.
   * @param $source
   */
  public function setSource($source) {
    $this->source = $source . '/';
  }

  /**
   * The destination to copy to.
   * @param $destination
   */
  public function setDestination($destination) {
    $this->destination = $destination . '/';
  }

  /**
   * Paths to be excluded from the rsync command.
   * @param $path
   */
  public function addExclude($path) {
    $this->options[] = "--exclude=$path";
  }

  /**
   * Set flags on the rsync command.
   * @param $flags
   */
  public function setFlags($flags) {
    $this->flags = '-' . $flags;
  }
}