// Your Jenkins stages

// Start deploy
// Get depployer here https://deployer.org/docs/installation.html
stage ('Deploy container') {
  sh '/usr/local/bin/dep --file=_tools/deploy.php deploy ' + env.BRANCH_NAME 
}
// Finish deploy
