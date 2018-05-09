Function Remove-LTIDataWithLogging
{
  [CmdletBinding()]
  param
  (
    [string] $ServerInstance,
    [string] $Database,
    [bool] $OutputSqlErrors,
    [ValidateSet('lti_coachingreports', 'lti_consumer', 'lti_context', 'lti_nonce', 'lti_results')]
    [string] $Table,
    [string] $Content
  )

  $SQLParameters = @{
    ServerInstance  = $ServerInstance
    Database        = $Database
    OutputSqlErrors = $OutputSqlErrors
  }

  $result = Invoke-SqlCmd @SQLParameters -Query "SELECT Count(*) FROM dbo.$Table WHERE consumer_key = '$Content'" -As DataSet
  $count = $result.Tables[0].Rows[0].Item(0)
  if ($count -gt 0) {
    Write-Verbose "Deleting $count entries from dbo.$Table"
    Invoke-SqlCmd @SQLParameters -Query "DELETE FROM dbo.$Table WHERE consumer_key = '$Content'"
  }
}
