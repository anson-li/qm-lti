<#
.SYNOPSIS
This script will access the LTI database and delete all entries
on the LTI that correspond to a specific tenant.
.DESCRIPTION
This script will access the LTI database and identify all customer entries that are attached to a specific client.
Once the customer data is deleted, all corresponding entries in the results, attempts and other tables are cleared if they
match the same customer key ID.

For OnPremise / OD4Gov / customers using the 'Debug mode' option, please input the enture URL used to configure the LTI Connector.
.EXAMPLE
.\DeleteTenantFromLTI.ps1 -TenantIDOrURL 20033
.PARAMETER TenantID
Your Tenant ID
#>

[CmdletBinding()]
param
(
  [Parameter(Position=0, Mandatory=$true)]
  [ValidateNotNullOrEmpty()]
  [string] $TenantIDOrURL
)

# Load shared functions
Import-Module $PSScriptRoot\Functions.psm1

# Init Script Behaviour
$ErrorActionPreference = "Stop"
$ProgressPreference = "SilentlyContinue"

# Load QMODHelper
Write-Host "Initialising QMODHelper..."
Import-Module QMODHelper

$dbInfo = Get-QM_DatabaseInfo -DatabaseType lti
$SQLParameters = @{
  ServerInstance  = $dbInfo.InstanceName
  Database        = $dbInfo.DatabaseName
  OutputSqlErrors = $true
}

try
{
  $result = Invoke-SqlCmd @SQLParameters -Query "SELECT consumer_key FROM dbo.lti_consumer WHERE customer_id = '$TenantIDOrURL'" -As DataSet
  if ($result -ne $null -And $result.Tables[0].Rows.Count -gt 0) {
    $resultArray = @()
    $result.Tables[0].Rows | Foreach-Object { $resultArray += $($_['consumer_key']) }
    Foreach ($consumerKey in $resultArray) {
      Write-Verbose "Deleting entries from $consumerKey"
      Remove-LTIDataWithLogging @SQLParameters 'lti_coachingreports' $consumerKey
      Remove-LTIDataWithLogging @SQLParameters 'lti_consumer' $consumerKey
      Remove-LTIDataWithLogging @SQLParameters 'lti_context' $consumerKey
      Remove-LTIDataWithLogging @SQLParameters 'lti_nonce' $consumerKey
      Remove-LTIDataWithLogging @SQLParameters 'lti_results' $consumerKey
    }
    Invoke-SqlCmd @SQLParameters -Query "DELETE FROM dbo.lti_customer WHERE customer_id = '$TenantIDOrURL'"
    Write-Host "LTI tenant data has been successfully removed."
  } else {
    Write-Warning "No data for that customer could be found. If the LTI was configured using Debug mode, please use the entire configuration URL and try again."
  }
}
catch
{
  Write-Error "Error occurred executing scheduled task:`n$_" -ErrorAction Continue
}

