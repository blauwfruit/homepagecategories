# homepagecategories

## Overview

Add specific shop categories & see more button to the homepage.

## Installation

### Backoffice Upload
1. Download the module package.
2. Go to the PrestaShop admin panel.
3. Navigate to `Modules` > `Module Manager`.
4. Click on `Upload a module` and select the downloaded package.
5. Install the module.

### Git Submodule
1. Navigate to your PrestaShop project's root directory.
2. Add the Homepage Categories module as a submodule: `git submodule add https://github.com/blauwfruit/homepagecategories modules/homepagecategories`
3. When you want to pull in the latest version run `git submodule update --init --recursive`

## Usage
### Shop Specific Homepage Categories:
1. Navigate to the Homepage Categories module configuration page `Design` > `Homepage Categories`
2. Click on add new
3. Search for a category name.
4. Hit the save button.

### See More Button:
1. Head to `Modules` > `Modules Manager`
2. Find the module and click configure.
3. Toggle the button to `Enabled`.
4. Hit the save button.

## Docker

For development or demo purposes you can run Docker to test this integration.

For the latest PrestaShop:
```bash
gh repo clone blauwfruit/homepagecategories .
docker compose up
```

For other version

```bash
gh repo clone blauwfruit/homepagecategories .
docker compose down --volumes && export TAG=8.1.7-8.1-apache && docker compose up
```