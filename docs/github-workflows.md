# Github workflows

[documentation](https://docs.github.com/en/actions/writing-workflows)

Configuration files are in `.github/workflows/` directory

For local test you can use [nektos/act](https://github.com/nektos/act) library

To test only one config per time use `act --workflows .github/workflows/phpstan-analyse.yml` command