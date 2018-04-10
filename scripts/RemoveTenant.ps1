#Requires -RunAsAdministrator
#Requires -Modules QMODHelper
[CmdletBinding()]
param()
Write-Verbose "Removing tenant from LTI"
# Init Script Behaviour
$ErrorActionPreference = 'Stop'
$ProgressPreference = 'SilentlyContinue'
# Initializing SQL provider
Import-Module SQLServer
$sqlUpgradeFilePath = Join-Path -Path $PSScriptRoot -ChildPath '2018_01-add-tenant-to-db-framework.sql'
$sqlGetAuthsourceFilePath = Join-Path -Path $PSScriptRoot -ChildPath '2018_01-retrieve-authsource-data-by-id.sql'
$sqlMigrateAuthsourceFilePath = Join-Path -Path $PSScriptRoot -ChildPath '2018_01-migrate-authsource-by-tenant.sql'
$repositoryBasePath = Get-SettingPathAsString -SettingsPath Legacy/RepositoryPath
$databaseHostName = (Get-QM_DatabaseInfo -DatabaseType Legacy).InstanceName
$databaseName = 'SimpleSaml'
$databaseUserName = 'SimpleSaml'
$databasePassword = New-AlphaNumericPassword
$sqlCmdArgs = @("DatabaseName=$DatabaseName")
$sqlCmdArgs += @("UserName=$DatabaseUserName")
$sqlCmdArgs += @("Password=$DatabasePassword")
# Load shared functions
Import-Module $PSScriptRoot\PortalSql.psm1
try {
  # Set the base format for updated SimpleSAML tables
  Invoke-SqlCmd -ServerInstance $databaseHostName -Variable $sqlCmdArgs -InputFile $sqlUpgradeFilePath -Database "master" -OutputSqlErrors $true -Verbose -ErrorAction Stop
  # Set a path mask for the full path to all QMR files
  $qmrPathMask = Join-Path -Path $repositoryBasePath -ChildPath "*.qmr"
  # Get all the QMR Files
  $qmrFiles = @(Get-ChildItem $qmrPathMask)
  $qmrCount = $qmrFiles.Count
  Write-Verbose "Found $qmrCount QMR files to process"
  # Build empty variables to track errored areas
  $successCount = 0
  $failedAreas = @()
  foreach ($QMRPath in $qmrFiles) {
    Write-Verbose "Getting DB connectionstring from $QMRPath"
    try {
      # Load the QMR File which is XML
      $QMRXML = [xml](Get-Content $QMRPath)
      $QMRContent = $QMRXML.get_DocumentElement();
      # Get the Database username from the QMR File
      $DBUserName = $QMRContent.Database.Username
      # Get the Database password from the QMR File but it is encrypted
      # So call the encryption wrapper to decrypt it
      $EncryptedPassword = $QMRContent.Database.Password
      $DBPassword = ConvertFrom-PerceptionEncryptedString $EncryptedPassword
      Write-Verbose "Running SimpleSAMLphp query on $DBUserName"
      $result = Get-PortalDatabaseStringVariable -DatabaseHostName $databaseHostName -DatabaseName $DBUserName -DatabaseUserName $DBUserName -DatabasePassword $DBPassword -VariableName "simplesamlphp_auth_authsource"
      if ($null -eq $result) {
        Write-Verbose "Area $DBUserName does not have a SimpleSAMLphp authsource available"
      } else  {
        Write-Verbose "Area $DBUserName does have a SimpleSAMLphp authsource available: $result"
        $sqlVarArgs = $sqlCmdArgs
        $sqlVarArgs += @("AuthsourceId='$result'")
        $sqlVarArgs += @("TenantId='$DBUserName'")
        Invoke-SqlCmd -ServerInstance $databaseHostName -Variable $sqlVarArgs -InputFile $sqlGetAuthsourceFilePath -Database $databaseName -OutputSqlErrors $true -Verbose | ForEach-Object { $authsource_data = $($_.authsource_data) }
        # Check if authsource is a string or json array
        try {
          $converted_data = ConvertFrom-Json $authsource_data
          $validJson = $true
        } catch {
          Write-Verbose $_
          $validJson = $false
        }
        if ($validJson) {
          $sqlVarArgs += @("AuthsourceData='$authsource_data'")
          $sqlVarArgs += @("EntityID='$($converted_data.idp)'")
        } else {
          $sqlVarArgs += @("AuthsourceData='$authsource_data'")
          $sqlVarArgs += @("EntityID='$authsource_data'")
        }
        Invoke-SqlCmd -ServerInstance $databaseHostName -Variable $sqlVarArgs -InputFile $sqlMigrateAuthsourceFilePath -Database $databaseName -OutputSqlErrors $true -Verbose
        $successCount++
      }
      Write-Verbose "Finished SimpleSAMLphp query on $DBUserName"
    } catch {
      # If reading the QMR file, decrypting the pw or running the query failed
      # Add this area to the failed areas array
      Write-Verbose "Failed to check area $DBUserName"
      $failedAreas += $DBUserName
      Write-Verbose $_
    }
  }
} catch {
  $ErrorActionPreference= "Continue"
  Write-Error "Error occured executing scheduled task:"
  Write-Error $_
}
Write-Host -Object "SimpleSAMLphp upgrade completed for $successCount areas, failed for areas (if any): $failedAreas"
