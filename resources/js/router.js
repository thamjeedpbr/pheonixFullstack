import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from './stores/auth';

// Import Pages
import Login from './Pages/Auth/Login.vue';
import Dashboard from './Pages/Dashboard.vue';
import UsersIndex from './Pages/Users/Index.vue';
import MachinesIndex from './Pages/Machines/Index.vue';
import MaterialsIndex from './Pages/Materials/Index.vue';
import MachineTypesIndex from './Pages/MachineTypes/Index.vue';
import ProcessesIndex from './Pages/Processes/Index.vue';
import DepartmentsIndex from './Pages/Departments/Index.vue';
import ShiftsIndex from './Pages/Shifts/Index.vue';
import SectionsIndex from './Pages/Sections/Index.vue';
import StatusesIndex from './Pages/Statuses/Index.vue';
import OrdersIndex from './Pages/Orders/Index.vue';
import OrdersCreate from './Pages/Orders/Create.vue';
import OrdersEdit from './Pages/Orders/Edit.vue';
import OrdersShow from './Pages/Orders/Show.vue';
import FormsIndex from './Pages/Forms/Index.vue';
import FormsCreate from './Pages/Forms/Create.vue';
import FormsEdit from './Pages/Forms/Edit.vue';
import FormsShow from './Pages/Forms/Show.vue';

const routes = [
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { requiresGuest: true },
  },
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta: { requiresAuth: true },
  },
  {
    path: '/users',
    name: 'users.index',
    component: UsersIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/machines',
    name: 'machines.index',
    component: MachinesIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/materials',
    name: 'materials.index',
    component: MaterialsIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/machine-types',
    name: 'machine-types.index',
    component: MachineTypesIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/processes',
    name: 'processes.index',
    component: ProcessesIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/departments',
    name: 'departments.index',
    component: DepartmentsIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/shifts',
    name: 'shifts.index',
    component: ShiftsIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/sections',
    name: 'sections.index',
    component: SectionsIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/statuses',
    name: 'statuses.index',
    component: StatusesIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/orders',
    name: 'orders.index',
    component: OrdersIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/orders/create',
    name: 'orders.create',
    component: OrdersCreate,
    meta: { requiresAuth: true },
  },
  {
    path: '/orders/:id',
    name: 'orders.show',
    component: OrdersShow,
    meta: { requiresAuth: true },
    props: true,
  },
  {
    path: '/orders/:id/edit',
    name: 'orders.edit',
    component: OrdersEdit,
    meta: { requiresAuth: true },
    props: true,
  },
  {
    path: '/forms',
    name: 'forms.index',
    component: FormsIndex,
    meta: { requiresAuth: true },
  },
  {
    path: '/forms/create',
    name: 'forms.create',
    component: FormsCreate,
    meta: { requiresAuth: true },
  },
  {
    path: '/forms/:id',
    name: 'forms.show',
    component: FormsShow,
    meta: { requiresAuth: true },
    props: true,
  },
  {
    path: '/forms/:id/edit',
    name: 'forms.edit',
    component: FormsEdit,
    meta: { requiresAuth: true },
    props: true,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation Guards
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'login' });
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'dashboard' });
  } else {
    next();
  }
});

export default router;
