import routeImporter from '@enso-ui/ui/src/modules/importers/routeImporter';

const routes = routeImporter(require.context('./users', false, /.*\.js$/));
const RouterView = () => import('@enso-ui/ui/src/bulma/pages/Router.vue');

export default {
    path: 'users',
    component: RouterView,
    meta: {
        breadcrumb: 'users',
        route: 'administration.users.index',
    },
    children: routes,
};
