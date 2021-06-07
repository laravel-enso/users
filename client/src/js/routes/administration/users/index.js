const UserIndex = () => import('../../../pages/administration/users/Index.vue');

export default {
    name: 'administration.users.index',
    path: '',
    component: UserIndex,
    meta: {
        breadcrumb: 'index',
        title: 'Users',
    },
};
