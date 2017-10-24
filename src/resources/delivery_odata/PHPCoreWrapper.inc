<?php
/**
 * Copyright (C) 2014 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

/**
 * @file
 * Wrapper for PHP standard functions.
 *
 * @author Questionmark
 */

namespace Questionmark\qm_core\Wrappers;

/**
 * @codeCoverageIgnore
 */
class PHPCoreWrapper implements PHPCoreWrapperInterface {
  /**
   * http://php.net/curl_init
   */
  public function curl_init($url = NULL) {
    return curl_init($url);
  }

  /**
   * http://php.net/curl_setop
   */
  public function curl_setopt($ch, $option, $value) {
    return curl_setopt($ch, $option, $value);
  }

  /**
   * http://php.net/curl_exec
   */
  public function curl_exec($ch) {
    return curl_exec($ch);
  }

  /**
   * http://php.net/curl_getinfo
   */
  public function curl_getinfo($ch, $opt = 0) {
    return curl_getinfo($ch, $opt);
  }

  /**
   * http://php.net/curl_close
   */
  public function curl_close($ch) {
    return curl_close($ch);
  }

  /**
   * {@inheritdoc}
   */
  public function curl_errno($ch) {
    return curl_errno($ch);
  }

  /**
   * {@inheritdoc}
   */
  public function curl_strerror($ch) {
    return curl_strerror($ch);
  }

  /**
   * http://php.net/date
   */
  public function date($format, $timestamp = NULL) {
    return date($format, is_null($timestamp) ? $this->time() : $timestamp);
  }

  /**
   * http://php.net/time
   */
  public function time() {
    return time();
  }

  /**
   * http://php.net/fopen
   */
  public function fopen($filename, $mode) {
    return fopen($filename, $mode);
  }

  /**
   * http://php.net/fclose
   */
  public function fclose($handle) {
    return fclose($handle);
  }

  /**
   * http://php.net/fread
   */
  public function fread($handle, $length) {
    return fread($handle, $length);
  }

  /**
   * http://php.net/filesize
   */
  public function filesize($filename) {
    return filesize($filename);
  }

  /**
   * http://php.net/file_get_contents
   */
  public function file_get_contents($filename, $use_include_path = FALSE, $context = NULL) {
    return file_get_contents($filename, $use_include_path, $context);
  }

  /**
   * http://php.net/urlencode
   */
  public function urlencode($str) {
    return urlencode($str);
  }

  /**
   * http://php.net/http_build_query
   */
  public function http_build_query($query_data) {
    return http_build_query($query_data);
  }

  /**
   * http://php.net/microtime
   */
  public function microtime($get_as_float = FALSE) {
    return microtime($get_as_float);
  }
}
