define(['fos-routing', 'assets/json!fos-routing-data'], function (router, routes) {
  router.setRoutingData(routes);
  return router;
});