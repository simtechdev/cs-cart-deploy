# cs-cart-deploy

[Deployer](https://deployer.org/) & [Jenkins](https://www.jenkins.io/) example files for CS-Cart/MultiVendor CI/CD processes

## Requirements

- Deployer [https://deployer.org/docs/installation.html](https://deployer.org/docs/installation.html)
- Jenkins<sup>optional</sup> [https://www.jenkins.io/doc/book/installing/](https://www.jenkins.io/doc/book/installing/)

## Example cases of usage
`ToDo`

## Manual Deployer usage
`ToDo`

```sh
dep --file=_tools/deploy.php deploy prod
```

## Some useful CS-Cart/MultiVendor commands for CI/CD processes

### The correct way for CS-Cart cache clear 
```php
task('clear:cache',function(){
  cd(get('deploy_path'));
  run('/usr/bin/php admin.php --dispatch=storage.clear_cache');
});
```
