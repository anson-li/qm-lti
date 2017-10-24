<?php
/**
 * Copyright (C) 2014 Questionmark Computing Limited.
 * License GNU GPL version 2 or later (see LICENSE.TXT file)
 * There is NO WARRANTY, to the extent permitted by law.
 */

/**
 * @file
 * Site settings
 *
 * @author Questionmark
 */

class SiteSettings {
  /**
   * @return string Site name
   */
  public function getSiteName() {
    return variable_get('site_name');
  }

  /**
   * @return string Tenant ID
   */
  public function getTenantId() {
    return variable_get('qm_tenant_id');
  }

  /**
   * @return string Portal key container name
   */
  public function getPortalKeyContainerName() {
    return variable_get('qm_rsa_key_container_name');
  }

  /**
   * @return string Gen5 key container name
   */
  public function getGen5KeyContainerName() {
    return variable_get('qm_gen5_rsa_key_container_name');
  }

  /**
   * @return string Delivery key container name
   */
  public function getDeliveryKeyContainerName() {
    return variable_get('qm_delivery_rsa_key_container_name');
  }

  /**
   * @return integer administrator role id
   */
  public function getAdministratorRoleId() {
    return variable_get('user_admin_role');
  }

  /**
   * @return string Questionmark license file path.
   */
  public function getLicenseFilePath() {
    return variable_get('qm_license_file_path');
  }

  /**
   * @return string Questionmark public files base URL
   */
  public function getPublicFilesBaseUrl() {
    return variable_get('qm_file_public_url');
  }

  /**
   * @return string Custom CSS path (NULL if no custom CSS was uploaded)
   */
  public function getCustomCssPath() {
    return variable_get('qm_custom_css_path');
  }

  /**
   * {@inheritdoc}
   */
  public function getOverridenPermissions() {
    return variable_get('qm_override_permissions');
  }

  /**
   * {@inheritdoc}
   */
  public function getPortalVersion() {
    return variable_get('qm_portal_version');
  }

  /**
   * {@inheritdoc}
   */
  public function getEnvironmentType() {
    return variable_get('qm_environment_type');
  }
}
