define(['fos-routing', 'assets/json!fos-routing-data'], function (router, routes) {
  fos.Router.setData(routes);
  return router;
});