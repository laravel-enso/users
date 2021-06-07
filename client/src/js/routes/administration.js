import routeImporter from '@enso-ui/ui/src/modules/importers/routeImporter';

const routes = routeImporter(require.context('./administration', false, /.*\.js$/));
const RouterView = () => import('@enso-ui/ui/src/bulma/pages/Router.vue');

export default {
    path: '/administration',
    component: RouterView,
    meta: {
        breadcrumb: 'administration',
    },
    children: routes,
};
