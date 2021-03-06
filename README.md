![Magento 2 Tests](https://github.com/Skywire/m2-module-wordpress-api/workflows/Magento%202%20Tests/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/Skywire/m2-module-wordpress-api/badge.svg?branch=master)](https://coveralls.io/github/Skywire/m2-module-wordpress-api?branch=master)
[![Latest Stable Version](https://poser.pugx.org/skywire/wordpressapi/v/stable)](https://packagist.org/packages/skywire/wordpressapi)
[![Total Downloads](https://poser.pugx.org/skywire/wordpressapi/downloads)](https://packagist.org/packages/skywire/wordpressapi)

Skywire Magento 2 Wordpress API

Wordpress API integration for M2 

## Installation

Install via composer

`composer require skywire/wordpressapi`

## Admin Configuration

Configure the base URL of the Wordpress instance, this should be on a unique URL or subdomain, subdirectories are not explicitly supported but may work depending on your web server config.

Configure the WP API path, the default for this is `/wp-json/wp/v2` and should not need to be changed.

It is possible to password protect the wordpress install with HTTP Basic auth, if this is the case configure the username and password so that Magento can access the API. 

## Usage

A custom router will kick in before a 404 and check if the page slug exists in wordpress as a page, post or category.

If a match is found the wordpress content will be rendered inside a Magento template.

Layouts are controlled by standard layout XML.

## Siblings

There is an additional sibling block which is added to post pages by default, this will show other posts from the same category as the post you are currently viewing

The number of sibling posts can be modified via the layout.

## Advanced Custom Fields

https://www.advancedcustomfields.com/ is nearly always installed on the WordPress instance but the fields added do not automatically appear in the WP API.

To add them to the API data install https://wordpress.org/plugins/acf-to-rest-api/

This data can then be used in your post templates .phtml file via `$post->getAcf('footer_call_to_action_link')`
