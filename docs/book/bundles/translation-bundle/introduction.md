## Introduction

One of the main concepts of the translation bundle is that the translated model knows nothing about translation.
Translation is an additional layer, making it easy to add or remove translation later on. 
Translation data is therefore stored in separate database tables with a reference to the original data.
This bundle supports simple text translations as well as route and media translations.

The bundle uses the `Enhavo\Bundle\FrameworkBundle\Locale\LocaleResolverInterface` to decide which locale should be load.
