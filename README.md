Symfony2-WPBlogBundle
=====================

A Symfony 2 bundle to utilise a WordPress database. Useful if you want to use the WordPress back-end but do the front-end yourself.

To Install
==========
Add to composer dependacies.
"repositories": [
...
    {
        "type": "git",
        "url":  "https://github.com/Tom-Davidson/Symfony2-WPBlogBundle.git"
    }
...
],
"require": {
...
    "tomdavidson/wpblogbundle": "master@dev"
...
}

To /app/AppKernel.php include another bundle in the registerBundles() $bundes[]:
            new TomDavidson\WPBlogBundle\TomDavidsonWPBlogBundle(),

To /app/routing.yml add the routing info:
TomDavidsonWPBlogBundle:
    resource: "@TomDavidsonWPBlogBundle/Controller/"
    type:     annotation
    prefix:   /blog
