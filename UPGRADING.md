# How to upgrade

* [2654] - `RouteParser::pathFor()` and `RouteParser::relativePathFor()` are deprecated. Use `RouteParser::urlFor()` and `RouteParser::relativeUrlFor()`
* [2638] - `RouteCollector::pathFor()` is now deprecated. Use `RouteParser::urlFor()`
* [2622] - `Router` has been removed. It is now split into `RouteCollector`, `RouteRunner` and `RouteParser`
* [2555] - PSR-15 Middleware support was implemented at the cost of Double-Pass middleware being deprecated.
* [2529] - XmlRelay no longer ships with its own PSR-7 implementation you will need to provide your own before you can create/run an app.
* [2507] - Method names are now case sensitive when using `App::map()`.
* [2404] - XmlRelay 4 requires PHP 7.1 or higher
* [2398] - Error handling was extracted into its own middleware. Add `RoutingMiddleware` to your middleware pipeline to handle errors by default. See PR for more information.
* [2329] - If you were overriding the HTTP method using either the custom header or the body param, you need to add the `Middleware\MethodOverrideMiddleware` middleware to be able to override the method like before.
* [2290] - XmlRelay no longer ships with `Pimple` as container dependency so you need to supply your own. `App::__call()` has been deprecated.
